<?php
require_once __DIR__ . '/../core/Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createUser($data) {
        try {
            $params = [
                (int)$data['admin'],
                $data['username'],
                $data['email'],
                $data['password'],        // ya hasheado en controller
                $data['fullname'],
                $data['birthday'],
                (int)$data['gender'],
                $data['birth_country'],
                $data['country'],
                $data['avatar'] ?? null
            ];

            $types = [
                PDO::PARAM_INT,  // admin
                PDO::PARAM_STR,  // username
                PDO::PARAM_STR,  // email
                PDO::PARAM_STR,  // password (hash ~60)
                PDO::PARAM_STR,  // fullname
                PDO::PARAM_STR,  // birthday (YYYY-MM-DD)
                PDO::PARAM_INT,  // gender
                PDO::PARAM_STR,  // birth_country
                PDO::PARAM_STR,  // country
                $params[9] === null ? PDO::PARAM_NULL : PDO::PARAM_LOB // avatar
            ];

            $this->db->callSP('sp_create_user', $params, $types);
            return true;

        } catch (PDOException $e) {
            // Códigos típicos MySQL
            $driverCode = $e->errorInfo[1] ?? null;
            $msg = $e->getMessage();

            if ($driverCode === 1062) { // Duplicate entry
                throw new RuntimeException('El username o el email ya existen (duplicado).', 409);
            }
            if (str_contains($msg, 'Data too long')) {
                throw new RuntimeException('Dato demasiado largo (revisa longitud de password o tamaño de avatar).', 400);
            }
            throw new RuntimeException('Error al crear usuario: ' . $msg, 400);
        }
    }

    public function getAllUsers() {
        $this->db->callSP('sp_get_all_users');
        return []; // si tu SP devuelve filas, aquí puedes fetchAll
    }

    public function getUserById($id) {
        $this->db->callSP('sp_get_user_by_id', [(int)$id], [PDO::PARAM_INT]);
        return []; // idem comentario de arriba
    }
}
