<?php
class Database
{
    private $config;
    public $conn;

    public function __construct()
    {
        $configPath = __DIR__ . '/db_config.json';

        if (!file_exists($configPath)) {
            die(json_encode(["error" => "Config file not found"]));
        }

        $configContent = file_get_contents($configPath);
        $this->config = json_decode($configContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die(json_encode(["error" => "Invalid JSON in config file"]));
        }
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->config['host'] . ";dbname=" . $this->config['db_name'] . ";charset=utf8mb4",
                $this->config['username'],
                $this->config['password']
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo json_encode(["error" => "Error de conexión: " . $exception->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}
