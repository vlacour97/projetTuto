SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE IF NOT EXISTS business (
  ID int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  zip_code int(11) NOT NULL,
  city text COLLATE utf8mb4_unicode_ci NOT NULL,
  country text COLLATE utf8mb4_unicode_ci NOT NULL,
  description text COLLATE utf8mb4_unicode_ci NOT NULL,
  director_name text COLLATE utf8mb4_unicode_ci NOT NULL,
  phone int(11) NOT NULL,
  mail text COLLATE utf8mb4_unicode_ci NOT NULL,
  siren int(11) NOT NULL,
  partner tinyint(1) NOT NULL,
  creation_date datetime NOT NULL,
  deletion_date datetime NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS continuities (
  id int(11) NOT NULL AUTO_INCREMENT,
  label text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `fields` (
  ID int(11) NOT NULL AUTO_INCREMENT,
  label text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS linkcontinuities (
  ID int(11) NOT NULL AUTO_INCREMENT,
  ID_prop int(11) NOT NULL,
  ID_cont int(11) NOT NULL,
  PRIMARY KEY (ID),
  KEY ID_prop (ID_prop),
  KEY ID_cont (ID_cont)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS linkfields (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_prop int(11) NOT NULL,
  id_field int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY id_prop (id_prop),
  KEY id_field (id_field)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS propositions (
  ID int(11) NOT NULL AUTO_INCREMENT,
  ID_ent int(11) NOT NULL,
  label text COLLATE utf8mb4_unicode_ci NOT NULL,
  adress text COLLATE utf8mb4_unicode_ci NOT NULL,
  zip_code int(11) NOT NULL,
  city text COLLATE utf8mb4_unicode_ci NOT NULL,
  ville text COLLATE utf8mb4_unicode_ci NOT NULL,
  country text COLLATE utf8mb4_unicode_ci NOT NULL,
  latitude int(11) NOT NULL,
  longitude int(11) NOT NULL,
  description text COLLATE utf8mb4_unicode_ci NOT NULL,
  duration int(11) NOT NULL,
  skills text COLLATE utf8mb4_unicode_ci NOT NULL,
  remuneration tinyint(3) NOT NULL,
  creation_date datetime NOT NULL,
  deletion_date datetime NOT NULL,
  PRIMARY KEY (ID),
  KEY ID_ent (ID_ent),
  KEY ID_ent_2 (ID_ent)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;


ALTER TABLE linkcontinuities
  ADD CONSTRAINT linkcontinuities_ibfk_1 FOREIGN KEY (ID_cont) REFERENCES continuities (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT linkcontinuities_ibfk_2 FOREIGN KEY (ID_prop) REFERENCES propositions (ID) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE linkfields
  ADD CONSTRAINT linkfields_ibfk_1 FOREIGN KEY (id_prop) REFERENCES propositions (ID) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT linkfields_ibfk_2 FOREIGN KEY (id_field) REFERENCES fields (ID) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE propositions
  ADD CONSTRAINT propositions_ibfk_1 FOREIGN KEY (ID_ent) REFERENCES business (ID);
