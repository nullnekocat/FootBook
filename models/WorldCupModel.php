<?php
namespace Models;
use PDO;
use Database;

require_once __DIR__ . '/../core/Database.php';

class WorldCupModel {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Funciones GET para obtener datos de los mundiales con sus imagenes en formato base64
    public function getAllWorldCups(): array {
        $stmt = $this->db->callSP('sp_get_worldcups_data'); 
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows ?: [];
    }
    public function getWorldCupById(int $id): array {
        $stmt = $this->db->callSP('sp_get_worldcup_by_id', [$id], [PDO::PARAM_INT]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows ?: [];
    }     

}
 