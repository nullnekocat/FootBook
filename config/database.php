<?php
class Database {
    private $host = "127.0.0.1";
    private $db_name = "footbook_db";
    private $username = "silkweb_admin";
    private $password = "3p2e43NqnNk3";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo json_encode(["error" => "Error de conexión: " . $exception->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}
?>
