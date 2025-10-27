<?php
class Database {
    private $pdo;
    private $stmt;

    public function __construct() {
        $config = json_decode(file_get_contents(__DIR__ . '/../db_config.json'), true);

        $dsn = "mysql:host={$config['host']};dbname={$config['db_name']};charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("❌ Error de conexión: " . $e->getMessage());
        }
    }

    public function callSP($spName, $params = []) {
        // Generar placeholders dinámicos según cantidad de parámetros
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL {$spName}($placeholders)";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute(array_values($params));
        return $this->stmt;
    }

    public function fetchAll() {
        return $this->stmt->fetchAll();
    }

    public function fetch() {
        return $this->stmt->fetch();
    }

    public function closeCursor() {
        $this->stmt->closeCursor();
    }
}
