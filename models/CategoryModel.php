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
        return $this->db->callSPFetchAll('sp_get_categorys');
    }
}
