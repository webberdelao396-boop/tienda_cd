<?php
/** header.php  -  Cabecera comun a todas las paginas publicas */
if (!function_exists('cfg')) { require_once __DIR__ . '/db.php'; }
$activa = $activa ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo ?? cfg('sitio_nombre')) ?> · <?= e(cfg('sitio_nombre')) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,900;1,9..144,500&family=Spectral:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="/index.php">
      <span class="brand-mark">◉</span>
      <span class="brand-text">
        <span class="brand-name"><?= e(cfg('sitio_nombre')) ?></span>
        <span class="brand-lema"><?= e(cfg('sitio_lema')) ?></span>
      </span>
    </a>
    <nav class="main-nav">
      <a href="/index.php"    class="<?= $activa === 'inicio'   ? 'is-active' : '' ?>">Inicio</a>
      <a href="/catalogo.php" class="<?= $activa === 'catalogo' ? 'is-active' : '' ?>">Catálogo</a>
      <a href="/catalogo.php?cat=libros">Libros</a>
      <a href="/catalogo.php?cat=vinilos">Vinilos</a>
      <a href="/catalogo.php?cat=cds">CDs</a>
      <a href="/contacto.php" class="<?= $activa === 'contacto' ? 'is-active' : '' ?>">Contacto</a>
    </nav>
  </div>
</header>
<main class="site-main">
