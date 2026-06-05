<?php
require_once __DIR__ . '/auth.php';
exigir_login();
require_once __DIR__ . '/../includes/partials.php';

$seccion = $_GET['seccion'] ?? 'inventario';

// ---- KPIs (indicadores administrativos) ----
$totalProd   = (int) db()->query("SELECT COUNT(*) FROM productos")->fetchColumn();
$valorInv    = (float) db()->query("SELECT COALESCE(SUM(precio*stock),0) FROM productos")->fetchColumn();
$agotados    = (int) db()->query("SELECT COUNT(*) FROM productos WHERE stock <= 0")->fetchColumn();
$sinLeer     = (int) db()->query("SELECT COUNT(*) FROM mensajes WHERE leido = 0")->fetchColumn();

$cats = db()->query("SELECT id, nombre FROM categorias ORDER BY id")->fetchAll();

// mensaje flash
$flash = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administración · Vinilo &amp; Letra</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,900&family=Spectral:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="admin-body">

<div class="admin-topbar">
  <span class="brand-name" style="font-family:var(--display);font-weight:900;">Vinilo &amp; Letra · Admin</span>
  <div>
    <span style="color:#f3ead8b0;margin-right:1rem;">👤 <?= e($_SESSION['admin']) ?></span>
    <a href="/index.php" style="color:var(--brass);margin-right:1rem;">Ver sitio</a>
    <a href="/admin/logout.php" style="color:var(--brass);">Salir</a>
  </div>
</div>

<div class="admin-wrap">

  <?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>

  <div class="kpi-grid">
    <div class="kpi"><div class="kpi-num"><?= $totalProd ?></div><div class="kpi-lbl">Productos</div></div>
    <div class="kpi"><div class="kpi-num"><?= precio($valorInv) ?></div><div class="kpi-lbl">Valor del inventario</div></div>
    <div class="kpi"><div class="kpi-num"><?= $agotados ?></div><div class="kpi-lbl">Agotados</div></div>
    <div class="kpi"><div class="kpi-num"><?= $sinLeer ?></div><div class="kpi-lbl">Mensajes sin leer</div></div>
  </div>

  <div class="admin-nav">
    <a href="?seccion=inventario" class="<?= $seccion==='inventario'?'is-active':'' ?>">Inventario y precios</a>
    <a href="?seccion=nuevo"      class="<?= $seccion==='nuevo'?'is-active':'' ?>">Agregar producto</a>
    <a href="?seccion=mensajes"   class="<?= $seccion==='mensajes'?'is-active':'' ?>">Atención al cliente (<?= $sinLeer ?>)</a>
  </div>

  <?php if ($seccion === 'inventario'): ?>
    <?php
      $prods = db()->query("
        SELECT p.*, c.nombre AS cat_nombre, c.slug AS cat_slug
        FROM productos p JOIN categorias c ON c.id = p.categoria_id
        ORDER BY c.id, p.titulo
      ")->fetchAll();
    ?>
    <p style="color:#f3ead8b0;margin-bottom:1rem;">Edita precio, stock, condición o destacado directamente y guarda cada fila.</p>
    <table class="tabla">
      <thead>
        <tr>
          <th>Producto</th><th>Categoría</th><th style="width:90px;">Precio</th>
          <th style="width:70px;">Stock</th><th style="width:130px;">Condición</th>
          <th style="width:70px;">Destacado</th><th style="width:150px;">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($prods as $p): ?>
        <tr>
          <form method="post" action="/admin/guardar.php">
            <input type="hidden" name="accion" value="actualizar">
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
            <td>
              <strong><?= e($p['titulo']) ?></strong><br>
              <span style="color:#f3ead8a0;font-size:.8rem;font-style:italic;"><?= e($p['autor_artista']) ?></span>
            </td>
            <td><?= e($p['cat_nombre']) ?></td>
            <td><input type="number" step="0.01" name="precio" value="<?= e(number_format((float)$p['precio'],2,'.','')) ?>"></td>
            <td><input type="number" name="stock" value="<?= (int)$p['stock'] ?>"></td>
            <td>
              <select name="condicion">
                <?php foreach (['Nuevo','Como nuevo','Muy bueno','Bueno','Aceptable'] as $cond): ?>
                  <option <?= $p['condicion']===$cond?'selected':'' ?>><?= $cond ?></option>
                <?php endforeach; ?>
              </select>
            </td>
            <td style="text-align:center;">
              <input type="checkbox" name="destacado" value="1" <?= $p['destacado']?'checked':'' ?>>
            </td>
            <td>
              <button class="admin-btn" type="submit">Guardar</button>
          </form>
              <form method="post" action="/admin/eliminar.php" style="display:inline;"
                    onsubmit="return confirm('¿Eliminar esta pieza del inventario?');">
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button class="admin-btn danger" type="submit">Eliminar</button>
              </form>
            </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

  <?php elseif ($seccion === 'nuevo'): ?>
    <h2 style="color:var(--brass);">Agregar nuevo producto</h2>
    <form method="post" action="/admin/guardar.php" class="form-card" style="background:#29221c;border-color:var(--brass);max-width:680px;">
      <input type="hidden" name="accion" value="crear">
      <div class="field"><label style="color:var(--paper);">Título</label>
        <input style="background:#1f1a16;color:var(--paper);border-color:var(--brass);" name="titulo" required></div>
      <div class="field"><label style="color:var(--paper);">Autor / Artista</label>
        <input style="background:#1f1a16;color:var(--paper);border-color:var(--brass);" name="autor_artista" required></div>
      <div class="field"><label style="color:var(--paper);">Categoría</label>
        <select style="background:#1f1a16;color:var(--paper);border-color:var(--brass);" name="categoria_id">
          <?php foreach ($cats as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['nombre']) ?></option><?php endforeach; ?>
        </select></div>
      <div class="field"><label style="color:var(--paper);">Año</label>
        <input type="number" style="background:#1f1a16;color:var(--paper);border-color:var(--brass);" name="anio"></div>
      <div class="field"><label style="color:var(--paper);">Condición</label>
        <select style="background:#1f1a16;color:var(--paper);border-color:var(--brass);" name="condicion">
          <?php foreach (['Nuevo','Como nuevo','Muy bueno','Bueno','Aceptable'] as $cond): ?><option><?= $cond ?></option><?php endforeach; ?>
        </select></div>
      <div class="field"><label style="color:var(--paper);">Precio (Q)</label>
        <input type="number" step="0.01" style="background:#1f1a16;color:var(--paper);border-color:var(--brass);" name="precio" value="0" required></div>
      <div class="field"><label style="color:var(--paper);">Stock</label>
        <input type="number" style="background:#1f1a16;color:var(--paper);border-color:var(--brass);" name="stock" value="1" required></div>
      <div class="field"><label style="color:var(--paper);">Descripción</label>
        <textarea style="background:#1f1a16;color:var(--paper);border-color:var(--brass);" name="descripcion"></textarea></div>
      <div class="field" style="flex-direction:row;align-items:center;gap:.5rem;">
        <input type="checkbox" name="destacado" value="1"><label style="color:var(--paper);">Marcar como destacado</label></div>
      <button class="admin-btn" type="submit">Crear producto</button>
    </form>

  <?php else: /* mensajes */ ?>
    <?php $msgs = db()->query("SELECT * FROM mensajes ORDER BY leido ASC, creado_en DESC")->fetchAll(); ?>
    <h2 style="color:var(--brass);">Mensajes de clientes</h2>
    <?php if (!$msgs): ?>
      <p style="color:#f3ead8b0;">Aún no hay mensajes.</p>
    <?php else: ?>
      <table class="tabla">
        <thead><tr><th>Fecha</th><th>Cliente</th><th>Asunto</th><th>Mensaje</th><th>Estado</th></tr></thead>
        <tbody>
        <?php foreach ($msgs as $m): ?>
          <tr style="<?= $m['leido']?'opacity:.6;':'' ?>">
            <td style="white-space:nowrap;"><?= e(date('d/m/Y H:i', strtotime($m['creado_en']))) ?></td>
            <td><?= e($m['nombre']) ?><br><span style="font-size:.8rem;color:var(--brass);"><?= e($m['email']) ?></span></td>
            <td><?= e($m['asunto']) ?></td>
            <td style="max-width:320px;"><?= nl2br(e($m['mensaje'])) ?></td>
            <td>
              <?php if ($m['leido']): ?>
                <span style="color:#f3ead8a0;font-size:.8rem;">Leído</span>
              <?php else: ?>
                <form method="post" action="/admin/guardar.php">
                  <input type="hidden" name="accion" value="leer">
                  <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
                  <button class="admin-btn" type="submit">Marcar leído</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  <?php endif; ?>

</div>
</body>
</html>
