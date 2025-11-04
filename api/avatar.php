<?php
require_once __DIR__ . '/../core/Database.php';
ini_set('display_errors', 0);
ob_clean();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(400); exit; }

$db = new Database();
$stmt = $db->callSP('sp_get_user_data', [$id], [PDO::PARAM_INT]);
$row  = $stmt->fetch(PDO::FETCH_ASSOC);
$db->finish($stmt);

if (!$row || empty($row['avatar'])) { http_response_code(404); exit; }

// Si siempre subes JPG/PNG, puedes intentar detectarlo; por simplicidad:
header('Content-Type: image/jpeg');
echo $row['avatar'];
exit;
