-- =====================================================================
--  Vinilo & Letra  -  Base de datos del comercio de coleccionables
--  Compatible con MySQL 8 y MariaDB 10+
-- =====================================================================
--  En Docker se carga sola.
--  En VirtualBox la importas con:
--      mysql -u root -p < init.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS coleccionables
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE coleccionables;

-- Por si reimportas: limpiamos en orden por las llaves foraneas
DROP TABLE IF EXISTS mensajes;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS admin_usuarios;

-- ---------------------------------------------------------------------
--  Categorias (segmentacion del catalogo)
-- ---------------------------------------------------------------------
CREATE TABLE categorias (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  nombre  VARCHAR(60)  NOT NULL,
  slug    VARCHAR(60)  NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO categorias (nombre, slug) VALUES
  ('Libros',  'libros'),
  ('Vinilos', 'vinilos'),
  ('CDs',     'cds');

-- ---------------------------------------------------------------------
--  Productos (inventario + precios)
-- ---------------------------------------------------------------------
CREATE TABLE productos (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id  INT NOT NULL,
  titulo        VARCHAR(150) NOT NULL,
  autor_artista VARCHAR(120) NOT NULL,
  anio          INT,
  condicion     ENUM('Nuevo','Como nuevo','Muy bueno','Bueno','Aceptable') DEFAULT 'Bueno',
  descripcion   TEXT,
  precio        DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock         INT NOT NULL DEFAULT 0,
  destacado     TINYINT(1) NOT NULL DEFAULT 0,
  imagen_url    VARCHAR(255),
  creado_en     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_categoria FOREIGN KEY (categoria_id)
      REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO productos
  (categoria_id, titulo, autor_artista, anio, condicion, descripcion, precio, stock, destacado, imagen_url) VALUES
  -- Libros (categoria 1)
  (1, 'Cien anos de soledad - 1a edicion', 'Gabriel Garcia Marquez', 1967, 'Aceptable',
      'Ejemplar de coleccion, tapa dura con desgaste leve en el lomo. Pieza para coleccionistas serios.', 1850.00, 1, 1, NULL),
  (1, 'Rayuela', 'Julio Cortazar', 1963, 'Muy bueno',
      'Edicion Sudamericana en buen estado, paginas limpias.', 320.00, 3, 0, NULL),
  (1, 'El Hobbit - edicion ilustrada', 'J.R.R. Tolkien', 1979, 'Bueno',
      'Edicion con ilustraciones originales, sobrecubierta conservada.', 540.00, 2, 1, NULL),
  (1, 'Pedro Paramo', 'Juan Rulfo', 1955, 'Como nuevo',
      'Reimpresion de aniversario, practicamente intacta.', 210.00, 5, 0, NULL),

  -- Vinilos (categoria 2)
  (2, 'The Dark Side of the Moon', 'Pink Floyd', 1973, 'Muy bueno',
      'Prensado original con poster y stickers incluidos. Vinilo sin rayones audibles.', 980.00, 1, 1, NULL),
  (2, 'Abbey Road', 'The Beatles', 1969, 'Bueno',
      'Edicion britanica, funda con desgaste en esquinas.', 1200.00, 1, 1, NULL),
  (2, 'Kind of Blue', 'Miles Davis', 1959, 'Aceptable',
      'Jazz de coleccion, reprensado de los 80s.', 450.00, 2, 0, NULL),
  (2, 'Rumours', 'Fleetwood Mac', 1977, 'Como nuevo',
      'Reedicion en vinilo de 180g, sellado.', 650.00, 4, 0, NULL),

  -- CDs (categoria 3)
  (3, 'OK Computer', 'Radiohead', 1997, 'Como nuevo',
      'CD original con librillo completo, caja sin fisuras.', 220.00, 6, 1, NULL),
  (3, 'Thriller', 'Michael Jackson', 1982, 'Muy bueno',
      'Edicion especial remasterizada.', 180.00, 8, 0, NULL),
  (3, 'Nevermind', 'Nirvana', 1991, 'Bueno',
      'Caja original, librillo con leve uso.', 160.00, 5, 0, NULL),
  (3, 'The Wall (2 CD)', 'Pink Floyd', 1979, 'Como nuevo',
      'Set doble en estuche, impecable.', 290.00, 3, 1, NULL);

-- ---------------------------------------------------------------------
--  Mensajes del formulario de contacto (atencion al cliente)
-- ---------------------------------------------------------------------
CREATE TABLE mensajes (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  nombre    VARCHAR(120) NOT NULL,
  email     VARCHAR(150) NOT NULL,
  asunto    VARCHAR(150),
  mensaje   TEXT NOT NULL,
  leido     TINYINT(1) NOT NULL DEFAULT 0,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
--  Usuario administrador
--  Usuario: admin   /   Contrasena: admin123
--  (el hash corresponde a "admin123"; cambialo en produccion)
-- ---------------------------------------------------------------------
CREATE TABLE admin_usuarios (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  usuario   VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO admin_usuarios (usuario, password_hash) VALUES
  ('admin', '$2b$10$5SssNfRm0tvCbYgXEEhiheiF9B2I9eOWTbDM.l/vzUvIJunp8XAti');
