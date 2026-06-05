<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/partials.php';

$catSlug = $_GET['cat'] ?? '';
$buscar  = trim($_GET['q'] ?? '');

// Lista de categorias para los filtros
$cats = db()->query("SELECT id, nombre, slug FROM categorias ORDER BY id")->fetchAll();

// Construccion segura de la consulta con filtros opcionales
$sql = "SELECT p.*, c.slug AS cat_slug, c.nombre AS cat_nombre
        FROM productos p
        JOIN categorias c ON c.id = p.categoria_id
        WHERE 1=1";
$params = [];

if ($catSlug !== '') {
    $sql .= " AND c.slug = :slug";
    $params[':slug'] = $catSlug;
}
if ($buscar !== '') {
    $sql .= " AND (p.titulo LIKE :q1 OR p.autor_artista LIKE :q2)";
    $params[':q1'] = '%' . $buscar . '%';
    $params[':q2'] = '%' . $buscar . '%';
}
$sql .= " ORDER BY p.destacado DESC, p.titulo ASC";

$stmt = db()->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll();

$titulo = 'Catálogo';
$activa = 'catalogo';
require __DIR__ . '/includes/header.php';
?>

<h1 class="section-title">Catálogo</h1>

<form method="get" action="/catalogo.php" style="margin-bottom:1.2rem;display:flex;gap:.6rem;flex-wrap:wrap;">
  <input type="text" name="q" value="<?= e($buscar) ?>" placeholder="Buscar por título o autor/artista…"
         style="flex:1;min-width:220px;padding:.6rem .8rem;border:2px solid var(--ink);background:var(--paper);font-family:var(--body);">
  <?php if ($catSlug): ?><input type="hidden" name="cat" value="<?= e($catSlug) ?>"><?php endif; ?>
  <button class="btn" type="submit">Buscar</button>
</form>

<div class="filtros">
  <a class="filtro-chip <?= $catSlug === '' ? 'is-active' : '' ?>" href="/catalogo.php<?= $buscar ? '?q='.urlencode($buscar) : '' ?>">Todo</a>
  <?php foreach ($cats as $c): ?>
    <a class="filtro-chip <?= $catSlug === $c['slug'] ? 'is-active' : '' ?>"
       href="/catalogo.php?cat=<?= e($c['slug']) ?><?= $buscar ? '&q='.urlencode($buscar) : '' ?>">
      <?= e($c['nombre']) ?>
    </a>
  <?php endforeach; ?>
</div>

<?php if ($productos): ?>
  <p class="prod-meta" style="margin-bottom:1rem;"><?= count($productos) ?> resultado(s)</p>
  <div class="prod-grid">
    <?php foreach ($productos as $p) tarjeta_producto($p); ?>
  </div>
<?php else: ?>
  <p class="lead">No encontramos piezas con esos criterios. Prueba con otra categoría o término de búsqueda.</p>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
