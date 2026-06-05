<?php
require_once __DIR__ . '/auth.php';

// Si ya esta logueado, al panel
if (!empty($_SESSION['admin'])) { header('Location: /admin/index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave   = $_POST['clave'] ?? '';

    $stmt = db()->prepare("SELECT * FROM admin_usuarios WHERE usuario = :u");
    $stmt->execute([':u' => $usuario]);
    $u = $stmt->fetch();

    if ($u && password_verify($clave, $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin'] = $u['usuario'];
        header('Location: /admin/index.php');
        exit;
    }
    $error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Acceso · Administración</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,900&family=Spectral:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="admin-body">
  <div class="admin-login">
    <h1 style="text-align:center;color:var(--brass);font-family:var(--display);">Vinilo &amp; Letra</h1>
    <p style="text-align:center;color:#f3ead8b0;margin-bottom:1.4rem;">Panel de administración</p>

    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

    <form class="form-card" method="post" action="/admin/login.php">
      <div class="field">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required autofocus>
      </div>
      <div class="field">
        <label for="clave">Contraseña</label>
        <input type="password" id="clave" name="clave" required>
      </div>
      <button class="admin-btn" type="submit" style="width:100%;padding:.7rem;">Entrar</button>
      <p style="font-size:.78rem;color:#f3ead8a0;margin-top:1rem;text-align:center;">
        Demo: usuario <strong>admin</strong> · clave <strong>admin123</strong>
      </p>
    </form>
  </div>
</body>
</html>
