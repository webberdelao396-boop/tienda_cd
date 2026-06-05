<?php
require_once __DIR__ . '/includes/db.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = db()->prepare("
    SELECT p.*, c.slug AS cat_slug, c.nombre AS cat_nombre
    FROM productos p
    JOIN categorias c ON c.id = p.categoria_id
    WHERE p.id = :id
");
$stmt->execute([':id' => $id]);
$p = $stmt->fetch();

if (!$p) {
    http_response_code(404);
    $titulo = 'No encontrado';
    require __DIR__ . '/includes/header.php';
    echo '<h1 class="section-title">Pieza no encontrada</h1>';
    echo '<p class="lead">Es posible que ya se haya vendido. <a href="/catalogo.php">Vuelve al catálogo</a>.</p>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$agotado = (int)$p['stock'] <= 0;
$titulo  = $p['titulo'];
require __DIR__ . '/includes/header.php';
?>

<a class="volver" href="/catalogo.php?cat=<?= e($p['cat_slug']) ?>">← Volver a <?= e($p['cat_nombre']) ?></a>

<div class="detalle">
  <div class="prod-cover <?= e($p['cat_slug']) ?>">
    <?php if (!empty($p['destacado'])): ?><span class="badge-destacado">Destacado</span><?php endif; ?>
    <span class="cover-letter"><?= e(strtoupper(mb_substr($p['titulo'],0,1))) ?></span>
    <span class="cover-type"><?= e($p['cat_nombre']) ?></span>
  </div>

  <div>
    <h1><?= e($p['titulo']) ?></h1>
    <p class="prod-artist" style="font-size:1.2rem;"><?= e($p['autor_artista']) ?></p>

    <table class="ficha">
      <tr><th>Categoría</th><td><?= e($p['cat_nombre']) ?></td></tr>
      <tr><th>Año</th><td><?= $p['anio'] ? (int)$p['anio'] : '—' ?></td></tr>
      <tr><th>Condición</th><td><?= e($p['condicion']) ?></td></tr>
      <tr><th>Disponibilidad</th><td><?= $agotado ? 'Agotado' : ((int)$p['stock'] . ' unidad(es)') ?></td></tr>
    </table>

    <p style="max-width:55ch;"><?= nl2br(e($p['descripcion'])) ?></p>

    <p class="precio-grande"><?= precio((float)$p['precio']) ?></p>

    <?php if ($agotado): ?>
      <span class="stock-pill agotado" style="font-size:.9rem;">Pieza agotada</span>
    <?php else: ?>
      <a class="btn" href="/contacto.php?asunto=<?= urlencode('Consulta: ' . $p['titulo']) ?>">Consultar / Reservar</a>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
