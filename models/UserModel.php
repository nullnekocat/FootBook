<?php
namespace Models;
use PDO;
use PDOException;
use Database;
use RuntimeException;


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
                $data['password'],
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

    public function getUserById($id): array { //No esta en uso
        $id = (int)$id; 
        $rows = $this->db->callView('v_lista_de_usuarios', "WHERE status = 1 AND id = {$id} LIMIT 1") ?: [];
        return $rows;
    }
    
    public function getUserForLogin(string $identity) {
        $stmt = $this->db->callSP('sp_get_user_for_login', [$identity], [PDO::PARAM_STR]);
        $user = $stmt->fetch(); // devuelve una fila o false
        $stmt->closeCursor();
        return $user ?: null;
    }
    
    public function getUserDataById(int $id): ?array {
        $id = (int)$id;
        $rows = $this->db->callView('v_lista_de_usuarios', "WHERE status = 1 AND id = {$id} LIMIT 1") ?: []; //Devuelve datos de 1 usuario
        return $rows ?: null;
    }

    public function getListOfUsers(): array {
        return $this->db->callView('v_lista_ligera_de_usuarios', 'WHERE status = 1 ORDER BY id ASC'); //para hacer listado de usuarios
    }

    public function updateUser(array $data): bool {
        try {
            // Construir los parámetros dinámicamente
            $params = [(int)$data['id']]; // p_id siempre va primero
            $types = [PDO::PARAM_INT];
            
            // Campos que pueden actualizarse
            $fields = [
                'fullname', 'username', 'email', 'birthday', 
                'gender', 'birth_country', 'country', 'avatar', 'password'
            ];
            
            foreach ($fields as $field) {
                if (isset($data[$field])) {
                    $params[] = $data[$field];
                    
                    // Determinar el tipo de dato
                    if ($field === 'gender') {
                        $types[] = PDO::PARAM_INT;
                    } else if ($field === 'avatar') {
                        $types[] = ($data[$field] === null ? PDO::PARAM_NULL : PDO::PARAM_LOB);
                    } else {
                        $types[] = PDO::PARAM_STR;
                    }
                } else {
                    // Si no viene el campo, enviar NULL para que no se actualice
                    $params[] = null;
                    $types[] = PDO::PARAM_NULL;
                }
            }

            $stmt = $this->db->callSP('sp_update_user_profile', $params, $types);
            $stmt->closeCursor();
            
            return true;

        } catch (PDOException $e) {
            $driverCode = $e->errorInfo[1] ?? null;
            $msg = $e->getMessage();

            if ($driverCode === 1062) { // Duplicate entry
                throw new RuntimeException('El username o el email ya están en uso.', 409);
            }
            throw new RuntimeException('Error al actualizar usuario: ' . $msg, 400);
        }
    }

    public function sp_soft_delete_user(int $id): array{
        try {
            $stmt = $this->db->callSP('sp_soft_delete_user', [$id], [PDO::PARAM_INT]);
            $row  = $stmt->fetch(PDO::FETCH_ASSOC); // el SP devuelve 1 registro de resumen
            $stmt->closeCursor();
            return $row ?: [];
        } catch (\PDOException $e) {
            throw new \RuntimeException('Error al dar de baja al usuario: ' . $e->getMessage(), 400, $e);
        }
    }
}
