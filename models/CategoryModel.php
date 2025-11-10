<?php
// models/CategoryModel.php
namespace Models;
use PDO;
use Database;

require_once __DIR__ . '/../core/Database.php';

class CategoryModel {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    /** Crear categoría */
    public function createCategory(array $data) {
        $name = trim($data['name'] ?? '');
        $this->db->callSP('sp_create_category', [$name], [PDO::PARAM_STR]);
    }

    /** Listar categorías (A→Z por nombre o ASC por id según tu SP) */
    public function getListOfCategory(): array {
        return $this->db->callView('v_lista_de_categorias', 'WHERE status = 1 ORDER BY id ASC');
    }

       /** Actualizar categoría */
    public function updateCategory(int $id, string $name): array {
        $stmt = $this->db->callSP('sp_update_category', [$id, $name], [PDO::PARAM_INT, PDO::PARAM_STR]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->finish($stmt);
        return $row ?: [];
    }

    /** Eliminar categoría (soft delete) */
    public function deleteCategory(int $id): array {
        $stmt = $this->db->callSP('sp_soft_delete_category', [$id], [PDO::PARAM_INT]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->finish($stmt);
        return $row ?: [];
    }
}
