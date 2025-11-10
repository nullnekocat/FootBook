<?php
// models/WorldCupModel.php
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
        return $this->db->callView('v_lista_de_mundiales', 'WHERE status = 1 ORDER BY id ASC') ?: [];
    }
    public function getWorldCupById(int $id): array {
        $id = (int)$id; // sanity cast
        // Devuelve el/los registros; si esperas uno, puedes quedarte con [0] abajo
        $rows = $this->db->callView('v_lista_de_mundiales', "WHERE status = 1 AND id = {$id} LIMIT 1") ?: [];
        return $rows; // o: return $rows[0] ?? [];
    }
    public function getAllWorldCupsLight(): array {                 
        return $this->db->callView('v_lista_ligera_de_mundiales', 'WHERE status = 1 ORDER BY id ASC') ?: []; 
    }    

}
/*
NOTAS:
v_lista_ligera_de_mundiales: La tabla no devuelve imagen
v_lista_de_mundiales: La tabla devuelve absolutamente todo
 */