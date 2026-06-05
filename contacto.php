<?php
require_once __DIR__ . '/includes/db.php';

$errores = [];
$ok = false;
$nombre = $email = $mensaje = '';
$asunto = $_GET['asunto'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre']  ?? '');
    $email   = trim($_POST['email']   ?? '');
    $asunto  = trim($_POST['asunto']  ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre === '')                              $errores[] = 'El nombre es obligatorio.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))  $errores[] = 'El correo no es válido.';
    if (mb_strlen($mensaje) < 10)                    $errores[] = 'El mensaje debe tener al menos 10 caracteres.';

    if (!$errores) {
        $stmt = db()->prepare("
            INSERT INTO mensajes (nombre, email, asunto, mensaje)
            VALUES (:n, :e, :a, :m)
        ");
        $stmt->execute([
            ':n' => $nombre,
            ':e' => $email,
            ':a' => $asunto !== '' ? $asunto : 'Sin asunto',
            ':m' => $mensaje,
        ]);
        $ok = true;
        $nombre = $email = $asunto = $mensaje = '';
    }
}

$titulo = 'Contacto';
$activa = 'contacto';
require __DIR__ . '/includes/header.php';
?>

<h1 class="section-title">Contacto</h1>
<p class="lead">¿Buscas una pieza específica, quieres vender tu colección o tienes una consulta? Escríbenos y te respondemos a la brevedad.</p>

<?php if ($ok): ?>
  <div class="alert ok">¡Gracias! Tu mensaje fue recibido. Te responderemos al correo indicado.</div>
<?php endif; ?>

<?php if ($errores): ?>
  <div class="alert err">
    <strong>Revisa lo siguiente:</strong>
    <ul style="margin:.4rem 0 0;">
      <?php foreach ($errores as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form class="form-card" method="post" action="/contacto.php">
  <div class="field">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" value="<?= e($nombre) ?>" required>
  </div>
  <div class="field">
    <label for="email">Correo electrónico</label>
    <input type="email" id="email" name="email" value="<?= e($email) ?>" required>
  </div>
  <div class="field">
    <label for="asunto">Asunto</label>
    <input type="text" id="asunto" name="asunto" value="<?= e($asunto) ?>" placeholder="Ej. Busco un vinilo de…">
  </div>
  <div class="field">
    <label for="mensaje">Mensaje</label>
    <textarea id="mensaje" name="mensaje" required><?= e($mensaje) ?></textarea>
  </div>
  <button class="btn" type="submit">Enviar mensaje</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>
