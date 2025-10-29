<?php
class Database {
    private $pdo;

    public function __construct() {
        $config = json_decode(file_get_contents(__DIR__ . '/../db_config.json'), true);
        $dsn = "mysql:host={$config['host']};dbname={$config['db_name']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    // $params = valores; $types = tipos por índice (opcional)
    public function callSP(string $spName, array $params = [], array $types = []) {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL {$spName}($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        // bind por posición para soportar LOB
        foreach ($params as $i => $val) {
            $pos  = $i + 1;
            $type = $types[$i] ?? (
                is_int($val)   ? PDO::PARAM_INT  :
                (is_null($val) ? PDO::PARAM_NULL : PDO::PARAM_STR)
            );
            if ($type === PDO::PARAM_LOB) {
                $stmt->bindParam($pos, $params[$i], PDO::PARAM_LOB);
            } else {
                $stmt->bindValue($pos, $val, $type);
            }
        }

        $stmt->execute();
        // consumir posibles result sets del SP
        while ($stmt->nextRowset()) { /* no-op */ }
        return $stmt;
    }
}
