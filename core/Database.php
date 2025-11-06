<?php
//core/Database.php
class Database {
    private $pdo;

    public function __construct() {
        $cfg = json_decode(file_get_contents(__DIR__ . '/../db_config.json'), true);
        $dsn = "mysql:host={$cfg['host']};dbname={$cfg['db_name']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function callSPFetchAll(string $spName, array $params = []): array {
        $ph = $params ? implode(',', array_fill(0, count($params), '?')) : '';
        $sql = "CALL {$spName}({$ph})";
        $stmt = $this->pdo->prepare($sql);

        // bind 1-based
        foreach ($params as $i => $v) {
            $stmt->bindValue($i + 1, $v);
        }

        $stmt->execute();

        // lee el primer result set
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // drena posibles result sets extra para liberar la conexión
        while ($stmt->nextRowset()) { /* noop */ }
        $stmt->closeCursor();

        return $rows;
    }
    
    public function callSP(string $sp, array $params = [], array $types = []): PDOStatement {
        $ph = implode(',', array_fill(0, count($params), '?'));
        $stmt = $this->pdo->prepare("CALL {$sp}($ph)");

        foreach ($params as $i => $val) {
            $pos  = $i + 1;
            $type = $types[$i] ?? (is_int($val) ? PDO::PARAM_INT : (is_null($val) ? PDO::PARAM_NULL : PDO::PARAM_STR));
            if ($type === PDO::PARAM_LOB) $stmt->bindParam($pos, $params[$i], PDO::PARAM_LOB);
            else                           $stmt->bindValue($pos, $val, $type);
        }

        $stmt->execute();
        // ¡OJO! Aquí NO hacemos nextRowset ni closeCursor.
        return $stmt;
    }

    // utilitario para cerrar bien después de fetch()
    public function finish(PDOStatement $stmt): void {
        // consume cualquier result set pendiente y cierra
        while ($stmt->nextRowset()) { /* no-op */ }
        $stmt->closeCursor();
    }
}
