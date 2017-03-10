DELIMITER $$
DROP FUNCTION IF EXISTS `calc_dist`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `calc_dist` (`lat1` REAL, `long1` REAL, `lat2` REAL, `long2` REAL) RETURNS DOUBLE NO SQL
  RETURN (((acos(sin((lat1*pi()/180)) * sin((lat2*pi()/180)) + cos((lat1*pi()/180)) * cos((lat2*pi()/180)) * cos(((long1 - long2)*pi()/180))))*180/pi())*60*2.133)*0.86884636487$$

DROP FUNCTION IF EXISTS `count_student_on_prop`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `count_student_on_prop` (`param_id_prop` INT) RETURNS INT(11) NO SQL
  RETURN (SELECT count(linkstudentprop.ID_student) FROM linkstudentprop WHERE linkstudentprop.ID_prop = param_id_prop)$$

DELIMITER ;

DROP TABLE IF EXISTS `business`;
CREATE TABLE `business` (
  `ID` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `director_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `siren` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `partner` tinyint(1) NOT NULL,
  `creation_date` datetime NOT NULL,
  `deletion_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `continuities`;
CREATE TABLE `continuities` (
  `id` int(11) NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `fields`;
CREATE TABLE `fields` (
  `ID` int(11) NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `ID` int(11) NOT NULL,
  `label` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `linkcontinuities`;
CREATE TABLE `linkcontinuities` (
  `ID` int(11) NOT NULL,
  `ID_prop` int(11) NOT NULL,
  `ID_cont` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `deletion_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `linkfields`;
CREATE TABLE `linkfields` (
  `id` int(11) NOT NULL,
  `id_prop` int(11) NOT NULL,
  `id_field` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `deletion_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `linkstudentprop`;
CREATE TABLE `linkstudentprop` (
  `ID` int(11) NOT NULL,
  `ID_student` int(11) NOT NULL,
  `ID_prop` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `deletion_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `propositions`;
CREATE TABLE `propositions` (
  `ID` int(11) NOT NULL,
  `ID_ent` int(11) NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `skills` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remuneration` tinyint(3) NOT NULL,
  `creation_date` datetime NOT NULL,
  `deletion_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `ID` int(11) NOT NULL,
  `ID_group` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fname` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `phone` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `INE` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `zip_code` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `city` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `country` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `informations` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `creation_date` datetime NOT NULL,
  `deletion_date` datetime DEFAULT NULL,
  `birth_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `business`
ADD PRIMARY KEY (`ID`);

ALTER TABLE `continuities`
ADD PRIMARY KEY (`id`);

ALTER TABLE `fields`
ADD PRIMARY KEY (`ID`);

ALTER TABLE `groups`
ADD PRIMARY KEY (`ID`);

ALTER TABLE `linkcontinuities`
ADD PRIMARY KEY (`ID`),
ADD UNIQUE KEY `ID_prop_2` (`ID_prop`,`ID_cont`),
ADD KEY `ID_prop` (`ID_prop`),
ADD KEY `ID_cont` (`ID_cont`);

ALTER TABLE `linkfields`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `id_prop_2` (`id_prop`,`id_field`),
ADD KEY `id_prop` (`id_prop`),
ADD KEY `id_field` (`id_field`);

ALTER TABLE `linkstudentprop`
ADD UNIQUE KEY `ID_student` (`ID_student`),
ADD KEY `ID_prop` (`ID_prop`),
ADD KEY `ID_student_2` (`ID_student`);

ALTER TABLE `propositions`
ADD PRIMARY KEY (`ID`),
ADD KEY `ID_ent` (`ID_ent`),
ADD KEY `ID_ent_2` (`ID_ent`);

ALTER TABLE `students`
ADD PRIMARY KEY (`ID`),
ADD KEY `ID_group` (`ID_group`);


ALTER TABLE `business`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
ALTER TABLE `continuities`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `fields`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `groups`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `linkcontinuities`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
ALTER TABLE `linkfields`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
ALTER TABLE `propositions`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

ALTER TABLE `linkcontinuities`
ADD CONSTRAINT `linkcontinuities_ibfk_1` FOREIGN KEY (`ID_cont`) REFERENCES `continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `linkcontinuities_ibfk_2` FOREIGN KEY (`ID_prop`) REFERENCES `propositions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `linkfields`
ADD CONSTRAINT `linkfields_ibfk_1` FOREIGN KEY (`id_prop`) REFERENCES `propositions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `linkfields_ibfk_2` FOREIGN KEY (`id_field`) REFERENCES `fields` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `linkstudentprop`
ADD CONSTRAINT `linkstudentprop_ibfk_1` FOREIGN KEY (`ID_student`) REFERENCES `students` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `linkstudentprop_ibfk_2` FOREIGN KEY (`ID_prop`) REFERENCES `propositions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `propositions`
ADD CONSTRAINT `propositions_ibfk_1` FOREIGN KEY (`ID_ent`) REFERENCES `business` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `students`
ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`ID_group`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
