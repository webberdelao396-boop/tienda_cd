<?php
/** auth.php  -  Protege las paginas del panel. Incluir al inicio. */
require_once __DIR__ . '/../includes/db.php';

session_start();

/** Cierra la sesion y redirige al login si no hay admin autenticado. */
function exigir_login(): void
{
    if (empty($_SESSION['admin'])) {
        header('Location: /admin/login.php');
        exit;
    }
}
