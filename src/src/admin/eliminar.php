<?php
require_once __DIR__ . '/auth.php';
exigir_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare("DELETE FROM productos WHERE id = :id");
    $stmt->execute([':id' => (int)($_POST['id'] ?? 0)]);
}

header('Location: /admin/index.php?seccion=inventario&msg=' . urlencode('Producto eliminado.'));
exit;
