########### SELECT ############

#Classement Entreprises
SELECT DISTINCT table2.csop as countStudent,business.* FROM business, (SELECT *,count_student_on_prop(ID) as csop FROM propositions WHERE propositions.deletion_date IS NULL) as table2
WHERE table2.ID_ent = business.ID AND business.deletion_date IS NULL
ORDER BY table2.csop DESC;

#Evolution par jour
SELECT count(linkstudentprop.ID) as nbStudent, DATE(linkstudentprop.creation_date) as date FROM linkstudentprop
WHERE deletion_date IS NULL
GROUP BY DATE(linkstudentprop.creation_date)
ORDER BY linkstudentprop.creation_date;

#Informations générales
SELECT
  (SELECT count(linkstudentprop.ID) FROM linkstudentprop WHERE deletion_date IS NULL) as nbTrainee,
  (SELECT count(propositions.ID) FROM propositions WHERE deletion_date IS NULL) as nbPropositions,
  (SELECT count(business.ID) FROM business WHERE deletion_date IS NULL) as nbBusiness,
  (SELECT count(linkstudentprop.ID) FROM linkstudentprop WHERE deletion_date IS NULL)/(SELECT count(students.ID) FROM students WHERE deletion_date IS NULL) * 100 as percentOfFindJob;

#Liste Etudiants sans stage
SELECT * FROM students
WHERE students.ID NOT IN (SELECT ID_student FROM linkstudentprop) AND deletion_date IS NULL;

#Liste Etudiants
SELECT students.*, groups.label as 'group' FROM students,groups WHERE students.ID_group = groups.ID AND students.deletion_date IS NULL;

#Informations Etudiant
SELECT * FROM students,groups WHERE students.ID = :student;

#Liste Groupes
SELECT * FROM groups;

########### INSERT ############


#Ajout Etudiant
INSERT INTO students(ID,ID_group, name, fname, email, phone, INE, address, zip_code, city, country, informations, creation_date, birth_date)
VALUES (:ID,:ID_group, :name, :fname, :email, :phone, :INE, :address, :zip_code, :city, :country, :informations, NOW(), :birth_date);

#Ajout Entreprise
INSERT INTO business(name, address, zip_code, city, country, description, director_name, phone, mail, siren, partner, creation_date)
    VALUES (:name, :address, :zip_code, :city, :country, :description, :director_name, :phone, :mail, :siren, :partner, NOW());

#Ajout Proposition
INSERT INTO propositions(ID_ent, label, adress, zip_code, city, country, latitude, longitude, description, duration, skills, remuneration, creation_date)
    VALUES (:ID_ent, :label, :adress, :zip_code, :city, :country, :latitude, :longitude, :description, :duration, :skills, :remuneration, NOW());

#Ajout Domaines D'activité
INSERT INTO fields(label)
    VALUES (:label);

#Ajout Poursuite
INSERT INTO continuities(label)
    VALUES (:label);

#Ajout Groupes
INSERT INTO groups(label)
    VALUES (:label);

#Ajout d'un étudiant à un stage
INSERT INTO linkstudentprop(ID_student, ID_prop, creation_date)
    VALUES (:ID_student,:ID_prop,NOW());

#Ajout d'un domaine d'activité à un stage
INSERT INTO linkfields(id_prop, id_field)
    VALUES (:id_prop, :id_field);

#Ajout d'une poursuite possible à un stage
INSERT INTO linkcontinuities(ID_prop, ID_cont)
    VALUES (:ID_prop, :ID_cont);

########### UPDATE ############


#Modification Etudiant
UPDATE students
  SET
    ID = :ID,
    ID_group = :ID_group,
    name = :name,
    fname = :fname,
    email = :email,
    phone = :phone,
    INE = :INE,
    address = :address,
    zip_code = :zip_code,
    city = :city,
    country = :country,
    informations = :informations,
    birth_date = :birth_date
  WHERE ID = :last_id;

#Suppression Etudiant
UPDATE students
  SET
    deletion_date = NOW()
  WHERE ID = :ID;
UPDATE linkstudentprop
  SET
    deletion_date = NOW()
  WHERE ID_student = :ID;

#Modifier Entreprise
UPDATE business
  SET
    name = :name,
    address = :address,
    zip_code = :zip_code,
    city = :city,
    country = :country,
    description = :description,
    director_name = :director_name,
    phone = :phone,
    mail = :mail,
    siren = :siren,
    partner = :partner
  WHERE ID = :ID;

#Supprimer Enteprise
UPDATE business
  SET
    deletion_date = NOW()
  WHERE ID = :ID;
UPDATE propositions
  SET
    deletion_date = NOW()
  WHERE ID_ent = :ID;
UPDATE linkfields
  SET
    deletion_date = NOW()
  WHERE id_prop IN (SELECT ID FROM propositions WHERE ID_ent = :ID);
UPDATE linkcontinuities
  SET
    deletion_date = NOW()
  WHERE id_prop IN (SELECT ID FROM propositions WHERE ID_ent = :ID);

#Modifier propositions
UPDATE propositions
  SET
    ID_ent = :ID_ent,
    label = :label,
    adress = :adress,
    zip_code = :zip_code,
    city = :city,
    country = :country,
    latitude = :latitude,
    longitude = :longitude,
    description = :description,
    duration = :duration,
    skills = :skills,
    remuneration = :remuneration
  WHERE ID = :ID;

#Supprimer Propositions
UPDATE propositions
  SET
    deletion_date = NOW()
  WHERE ID = :ID;
UPDATE linkfields
  SET
    deletion_date = NOW()
  WHERE id_prop = :ID;
UPDATE linkcontinuities
  SET
    deletion_date = NOW()
  WHERE id_prop = :ID;

#Modifier Domaine d'activité
UPDATE fields
  SET
    label = :label
  WHERE ID = :ID;

#Modifier Poursuite
UPDATE continuities
  SET
    label = :label
  WHERE ID = :ID;

#Supprimer lien Domaine d'activité
UPDATE linkfields
  SET
    deletion_date = NOW()
  WHERE id = :ID;

#Supprimer lien Poursuite
UPDATE linkcontinuities
  SET
    deletion_date = NOW()
  WHERE ID = :ID;

#Retirer un étudiant d'un stage
UPDATE linkstudentprop
  SET
    deletion_date = NOW()
  WHERE ID_student = :ID;

########### DELETE ############

#Supprimer Domaine d'Activité
DELETE FROM fields WHERE ID = :ID;
DELETE FROM linkfields WHERE id_field = :ID;

#Supprimer Poursuite
DELETE FROM continuities WHERE ID = :ID;
DELETE FROM linkcontinuities WHERE ID_cont = :ID;