<?php
require_once __DIR__ . '/auth.php';
exigir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /admin/index.php'); exit; }

$accion = $_POST['accion'] ?? '';

switch ($accion) {

    case 'actualizar':
        $stmt = db()->prepare("
            UPDATE productos
            SET precio = :precio, stock = :stock, condicion = :condicion, destacado = :destacado
            WHERE id = :id
        ");
        $stmt->execute([
            ':precio'    => (float)($_POST['precio'] ?? 0),
            ':stock'     => (int)($_POST['stock'] ?? 0),
            ':condicion' => $_POST['condicion'] ?? 'Bueno',
            ':destacado' => isset($_POST['destacado']) ? 1 : 0,
            ':id'        => (int)($_POST['id'] ?? 0),
        ]);
        $msg = 'Producto actualizado.';
        $seccion = 'inventario';
        break;

    case 'crear':
        $stmt = db()->prepare("
            INSERT INTO productos
              (categoria_id, titulo, autor_artista, anio, condicion, descripcion, precio, stock, destacado)
            VALUES
              (:cat, :titulo, :autor, :anio, :condicion, :desc, :precio, :stock, :destacado)
        ");
        $stmt->execute([
            ':cat'       => (int)($_POST['categoria_id'] ?? 1),
            ':titulo'    => trim($_POST['titulo'] ?? ''),
            ':autor'     => trim($_POST['autor_artista'] ?? ''),
            ':anio'      => $_POST['anio'] !== '' ? (int)$_POST['anio'] : null,
            ':condicion' => $_POST['condicion'] ?? 'Bueno',
            ':desc'      => trim($_POST['descripcion'] ?? ''),
            ':precio'    => (float)($_POST['precio'] ?? 0),
            ':stock'     => (int)($_POST['stock'] ?? 0),
            ':destacado' => isset($_POST['destacado']) ? 1 : 0,
        ]);
        $msg = 'Producto creado correctamente.';
        $seccion = 'inventario';
        break;

    case 'leer':
        $stmt = db()->prepare("UPDATE mensajes SET leido = 1 WHERE id = :id");
        $stmt->execute([':id' => (int)($_POST['id'] ?? 0)]);
        $msg = 'Mensaje marcado como leído.';
        $seccion = 'mensajes';
        break;

    default:
        $msg = '';
        $seccion = 'inventario';
}

header('Location: /admin/index.php?seccion=' . urlencode($seccion) . '&msg=' . urlencode($msg));
exit;
