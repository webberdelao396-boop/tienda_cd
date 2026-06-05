<?php
/**
 * db.php  -  Conexion PDO + funciones de ayuda compartidas
 * Se incluye al inicio de cada pagina.
 */

$config = require __DIR__ . '/../config.php';

/**
 * Polyfills por si la instalacion de PHP en VirtualBox no trae mbstring.
 * Asi el sitio nunca se cae por estas funciones.
 */
if (!function_exists('mb_substr')) {
    function mb_substr($s, $start, $length = null, $enc = null) {
        return $length === null ? substr((string)$s, $start) : substr((string)$s, $start, $length);
    }
}
if (!function_exists('mb_strlen')) {
    function mb_strlen($s, $enc = null) { return strlen((string)$s); }
}

/**
 * Devuelve una conexion PDO unica (singleton).
 */
function db(): PDO
{
    global $config;
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $config['db_host'],
            $config['db_port'],
            $config['db_name']
        );
        try {
            $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
 PDO::ATTR_TIMEOUT => 30,
               ]);
        } catch (PDOException $e) {
            die('<h1>Error de conexion a la base de datos</h1>'
              . '<p>Revisa config.php (host/usuario/clave) y que MySQL este corriendo.</p>'
              . '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>');
        }
    }
    return $pdo;
}

/**
 * Escapa texto para mostrarlo en HTML de forma segura (anti XSS).
 */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Formatea un precio con simbolo de moneda.
 */
function precio(float $valor): string
{
    return 'Q ' . number_format($valor, 2);
}

/**
 * Devuelve el dato de configuracion del sitio.
 */
function cfg(string $clave): string
{
    global $config;
    return $config[$clave] ?? '';
}
