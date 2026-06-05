<?php
/**
 * config.php  -  Configuracion central
 * ---------------------------------------------------------------
 *  Este es el UNICO archivo que cambia entre Docker y VirtualBox.
 *
 *  - En Docker: las variables de entorno (getenv) vienen del
 *    docker-compose.yml, por eso DB_HOST = "db".
 *
 *  - En VirtualBox: NO hay variables de entorno, entonces se usan
 *    los valores por defecto de la derecha (?:), donde DB_HOST
 *    es "localhost" porque MySQL corre en la misma maquina.
 *
 *  Si en VirtualBox creaste un usuario distinto a root, solo
 *  edita 'db_user' y 'db_pass' aqui abajo.
 * ---------------------------------------------------------------
 */

return [
/*    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_port' => getenv('DB_PORT') ?: '3306',
    'db_name' => getenv('DB_NAME') ?: 'tienda_WG',
    'db_user' => getenv('DB_USER') ?: 'admin1',
    'db_pass' => getenv('DB_PASS') ?: 'Temp123',
*/
 'db_host' => getenv('DB_HOST') ?: 'acela.proxy.rlwy.net',
    'db_port' => getenv('DB_PORT') ?: '51840',
    'db_name' => getenv('DB_NAME') ?: 'railway',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: 'TwonEboiyCxjeldQPEhGjVuUWbiFmFIz',

    // Datos del sitio (se muestran en toda la web)
    'sitio_nombre'  => 'Vinilo & CD´s',
    'sitio_lema'    => 'Coleccionables Vintage',
    'sitio_email'   => 'wendel.delao@live.com',
];
