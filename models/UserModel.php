<?php
require_once __DIR__ . '/../core/Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

        public function createUser($data) {
        try {
            // Asegurarnos de que todos los valores estén definidos
            $params = [
                (int)$data['admin'],
                $data['username'],
                $data['email'],
                $data['password'],        
                $data['fullname'],
                $data['birthday'],
                (int)$data['gender'],
                $data['birth_country'],
                $data['country'],
                $data['avatar'] ?? null   // null si no hay imagen
            ];

            // Llamar al procedimiento
            $this->db->callSP('sp_create_user', $params);
            $this->db->closeCursor();
            return true;
        } catch (PDOException $e) {
            error_log("❌ Error en createUser: " . $e->getMessage());
            echo json_encode(["error" => $e->getMessage()]); // visible para depurar
            return false;
        }
    }

    public function getAllUsers() {
        try {
            $this->db->callSP('sp_get_all_users');
            $result = $this->db->fetchAll();
            $this->db->closeCursor();
            return $result;
        } catch (PDOException $e) {
            error_log("Error en getAllUsers: " . $e->getMessage());
            return [];
        }
    }

    public function getUserById($id) {
        $this->db->callSP('sp_get_user_by_id', [$id]);
        $user = $this->db->fetch();
        $this->db->closeCursor();
        return $user;
    }
}
