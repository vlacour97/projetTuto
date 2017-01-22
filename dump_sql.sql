DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `calc_dist`(`lat1` REAL, `long1` REAL, `lat2` REAL, `long2` REAL) RETURNS double
    NO SQL
RETURN (((acos(sin((lat1*pi()/180)) * sin((lat2*pi()/180)) + cos((lat1*pi()/180)) * cos((lat2*pi()/180)) * cos(((long1 - long2)*pi()/180))))*180/pi())*60*2.133)*0.86884636487$$

DELIMITER ;

CREATE TABLE IF NOT EXISTS `business` (
  `ID` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` int(11) NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `director_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` int(11) NOT NULL,
  `mail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `siren` int(11) NOT NULL,
  `partner` tinyint(1) NOT NULL,
  `creation_date` datetime NOT NULL,
  `deletion_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `continuities` (
  `id` int(11) NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `fields` (
  `ID` int(11) NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `linkcontinuities` (
  `ID` int(11) NOT NULL,
  `ID_prop` int(11) NOT NULL,
  `ID_cont` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `linkfields` (
  `id` int(11) NOT NULL,
  `id_prop` int(11) NOT NULL,
  `id_field` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `propositions` (
  `ID` int(11) NOT NULL,
  `ID_ent` int(11) NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` int(11) NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `skills` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remuneration` tinyint(3) NOT NULL,
  `creation_date` datetime NOT NULL,
  `deletion_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `business`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `continuities`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `fields`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `linkcontinuities`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_prop` (`ID_prop`),
  ADD KEY `ID_cont` (`ID_cont`);

ALTER TABLE `linkfields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_prop` (`id_prop`),
  ADD KEY `id_field` (`id_field`);

ALTER TABLE `propositions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_ent` (`ID_ent`),
  ADD KEY `ID_ent_2` (`ID_ent`);


ALTER TABLE `business`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `continuities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `fields`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `linkcontinuities`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `linkfields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `propositions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `linkcontinuities`
  ADD CONSTRAINT `linkcontinuities_ibfk_1` FOREIGN KEY (`ID_cont`) REFERENCES `continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `linkcontinuities_ibfk_2` FOREIGN KEY (`ID_prop`) REFERENCES `propositions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `linkfields`
  ADD CONSTRAINT `linkfields_ibfk_1` FOREIGN KEY (`id_prop`) REFERENCES `propositions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `linkfields_ibfk_2` FOREIGN KEY (`id_field`) REFERENCES `fields` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `propositions`
  ADD CONSTRAINT `propositions_ibfk_1` FOREIGN KEY (`ID_ent`) REFERENCES `business` (`ID`);