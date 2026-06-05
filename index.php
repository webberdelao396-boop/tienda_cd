<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/partials.php';

// Conteo por categoria (para las tarjetas de categoria)
$cats = db()->query("
    SELECT c.id, c.nombre, c.slug, COUNT(p.id) AS total
    FROM categorias c
    LEFT JOIN productos p ON p.categoria_id = c.id
    GROUP BY c.id, c.nombre, c.slug
    ORDER BY c.id
")->fetchAll();

// Productos destacados
$destacados = db()->query("
    SELECT p.*, c.slug AS cat_slug, c.nombre AS cat_nombre
    FROM productos p
    JOIN categorias c ON c.id = p.categoria_id
    WHERE p.destacado = 1
    ORDER BY p.creado_en DESC
    LIMIT 8
")->fetchAll();

$iconos = ['libros' => '📚', 'vinilos' => '🎶', 'cds' => '💿'];

$titulo = 'Inicio';
$activa = 'inicio';
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1>Tesoros que otros dejaron ir.</h1>
  <p>Libros descatalogados, vinilos originales y CDs de colección, curados pieza por pieza para quienes saben lo que buscan.</p>
  <div class="actions">
    <a class="btn" href="/catalogo.php">Ver el catálogo</a>
    <a class="btn btn-ghost" href="/contacto.php">Vender o consultar</a>
  </div>
</section>

<h2 class="section-title">Explora por categoría</h2>
<div class="cat-grid">
  <?php foreach ($cats as $c): ?>
    <a class="cat-card" href="/catalogo.php?cat=<?= e($c['slug']) ?>">
      <span class="cat-icon"><?= $iconos[$c['slug']] ?? '◉' ?></span>
      <h3><?= e($c['nombre']) ?></h3>
      <span class="cat-count"><?= (int)$c['total'] ?> piezas disponibles</span>
    </a>
  <?php endforeach; ?>
</div>

<h2 class="section-title">Piezas destacadas</h2>
<?php if ($destacados): ?>
  <div class="prod-grid">
    <?php foreach ($destacados as $p) tarjeta_producto($p); ?>
  </div>
<?php else: ?>
  <p class="lead">Aún no hay piezas destacadas. Vuelve pronto.</p>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
