<?php
// models/CommentModel.php
namespace Models;
use PDO;
use PDOException;
use Database;
use RuntimeException;

require_once __DIR__ . '/../core/Database.php';

class CommentModel{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    
    /* ========= Crear Comentario ========== */
    public function createComment(array $data) {
        try{
            $params = [
                (int)$data['post_id'],
                (int)$data['user_id'],
                $data['content']
            ];
            $types = [
                 PDO::PARAM_INT,  // post_id
                 PDO::PARAM_INT,  // user_id
                 PDO::PARAM_STR,  // content
            ];
            
            $stmt = $this->db->callSP('sp_create_comment', $params, $types);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            // Si esperas un único registro:
            return $rows[0] ?? [];
        }catch(PDOException $e){
            throw new \RuntimeException(
                'No fue posible crear el comentario (DB): ' . $e->getMessage(),
                (int)($e->getCode() ?: 500),
                $e
            );
        }catch (\Throwable $e) {
            // Cualquier otro error (validaciones, etc.)
            throw new \RuntimeException(
                'No fue posible crear el comentario: ' . $e->getMessage(),
                (int)($e->getCode() ?: 400),
                $e
            );
        }
    }
    public function getComments(int $post_id): array{
        try{
            $post_id = (int)$post_id;
            $rows = $this->db->callView('v_lista_de_comentarios', "WHERE status = 1 AND post_id = {$post_id}") ?: [];
            return $rows;
        }catch (PDOException $e) {
            throw new RuntimeException('Error al obtener comentarios: ' . $e->getMessage(), 400);
        }
    }
}