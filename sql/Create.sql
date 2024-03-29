
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `codepost` int(11) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `user_UN` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `hotel` (
  `id_hotel` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `qualite` int(11) NOT NULL,
  `etoile` int(11) NOT NULL,
  PRIMARY KEY (`id_hotel`),
  UNIQUE KEY `hotel_UN` (`adresse`,`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `chambre` (
  `id_chambre` int(11) NOT NULL AUTO_INCREMENT,
  `id_hotelch` int(11) NOT NULL,
  `is_dortoir` tinyint(1) NOT NULL,
  `prix` int(10) unsigned NOT NULL,
  `nb_lits` int(10) unsigned NOT NULL,
  `img` varchar(100) NOT NULL,
  `nb_chambre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_chambre`),
  KEY `chambre_FK` (`id_hotelch`),
  CONSTRAINT `chambre_FK` FOREIGN KEY (`id_hotelch`) REFERENCES `hotel` (`id_hotel`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `reservation` (
  `id_res` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_chambre` int(11) NOT NULL,
  `date_deb` date NOT NULL,
  `date_fin` date NOT NULL,
  `nb_lit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_res`),
  KEY `reservation_FK_chambre` (`id_chambre`),
  KEY `reservation_FK_user` (`id_user`),
  CONSTRAINT `reservation_FK_chambre` FOREIGN KEY (`id_chambre`) REFERENCES `chambre` (`id_chambre`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_FK_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
