-- Tabla para trazabilidad de instrumental
CREATE TABLE IF NOT EXISTS `trazabilidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_instrumento` int(11) NOT NULL,
  `id_procedimiento` int(11) DEFAULT NULL,
  `id_instrumentador` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `tipo_evento` enum('esterilizacion','uso','mantenimiento','baja','conteo') DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado_previo` varchar(50) DEFAULT NULL,
  `estado_nuevo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_instrumento` (`id_instrumento`),
  KEY `id_procedimiento` (`id_procedimiento`),
  KEY `id_instrumentador` (`id_instrumentador`),
  CONSTRAINT `trazabilidad_instrumento` FOREIGN KEY (`id_instrumento`) REFERENCES `instrumento` (`id`),
  CONSTRAINT `trazabilidad_procedimiento` FOREIGN KEY (`id_procedimiento`) REFERENCES `procedimiento` (`id`),
  CONSTRAINT `trazabilidad_instrumentador` FOREIGN KEY (`id_instrumentador`) REFERENCES `instrumentador` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;

-- Tabla para conteo de instrumentos
CREATE TABLE IF NOT EXISTS `conteo_instrumentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_procedimiento` int(11) NOT NULL,
  `id_instrumentador` int(11) NOT NULL,
  `momento` enum('inicial','final') NOT NULL,
  `completo` tinyint(1) DEFAULT 0,
  `fecha` datetime DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_procedimiento` (`id_procedimiento`),
  KEY `id_instrumentador` (`id_instrumentador`),
  CONSTRAINT `conteo_procedimiento` FOREIGN KEY (`id_procedimiento`) REFERENCES `procedimiento` (`id`),
  CONSTRAINT `conteo_instrumentador` FOREIGN KEY (`id_instrumentador`) REFERENCES `instrumentador` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;

-- Tabla para detalle de conteo
CREATE TABLE IF NOT EXISTS `detalle_conteo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_conteo` int(11) NOT NULL,
  `id_instrumento` int(11) NOT NULL,
  `cantidad_esperada` int(11) NOT NULL DEFAULT 1,
  `cantidad_contada` int(11) NOT NULL DEFAULT 0,
  `faltante` tinyint(1) DEFAULT 0,
  `observaciones` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_conteo` (`id_conteo`),
  KEY `id_instrumento` (`id_instrumento`),
  CONSTRAINT `detalle_conteo_conteo` FOREIGN KEY (`id_conteo`) REFERENCES `conteo_instrumentos` (`id`),
  CONSTRAINT `detalle_conteo_instrumento` FOREIGN KEY (`id_instrumento`) REFERENCES `instrumento` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;
