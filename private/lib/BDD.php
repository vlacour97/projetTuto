<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 22/01/17
 * Time: 14:31
 */

namespace lib;

/**
 * Class BDD
 * @package lib
 * @author Valentin Lacour
 */
class BDD {

    /**
     * @var bool Variable de vérification d'initialisation
     */
    static private $init = false;
    /**
     * @var \PDO Objet de connexion à la BDD
     */
    static private $PDO;
    /**
     * @var string Prefix de table
     */
    static private $prefix = "";
    /**
     * @var float Latitude de comparaison
     */
    static private $default_lat = 49.89;
    /**
     * @var float Longitude de comparaison
     */
    static private $default_long = 2.295;

    /**
     * Fonction d'initialisation de BDD
     */
    static private function init(){
        if(self::$init)
            return true;
        $PDO_data = link_parameters('app/bd_datas');
        self::$PDO = new \PDO('mysql:dbname='.$PDO_data['db_name'].';host='.$PDO_data['host'].';charset=utf8',$PDO_data['login'],$PDO_data['password']);
        self::$init = true;
        self::$prefix = $PDO_data['prefix'];
    }

    //TODO remplacer .* par des as

    /**
     * Recherche de propositions
     * @param string $search Champ de recherche
     * @param string $country Pays demandé
     * @param string $field Domaine d'experience
     * @param string $continuity Poursuite possible
     * @param string $remuneration Possibilité de rémunération (0 => NSP, 1=> Oui, 2=> Non)
     * @return array
     */
    static public function search_query($partner= null, $search = "",$country = "",$field = "",$continuity = "",$remuneration = ""){
        self::init();
        $query_def = 'SELECT calc_dist(propositions.latitude,propositions.longitude,'.self::$default_lat.','.self::$default_long.') as distance,propositions.*,business.partner AS partner, business.name AS name
      FROM propositions,business,fields,continuities
      WHERE propositions.ID_ent = business.ID
      AND (business.name like :search OR propositions.label LIKE :search)
      AND propositions.country LIKE :country
      AND fields.id LIKE :field
      AND continuities.id LIKE :continuity
      AND propositions.remuneration LIKE :remuneration
      AND propositions.deletion_date is null
      AND business.deletion_date is null ';
        switch($partner){
            case true:
                $query_def .= 'AND business.partner = 1 ';
                break;
            case false:
                !($partner === null) && $query_def .= 'AND business.partner = 0 ';
                break;
        }
        $query_def .= 'GROUP BY propositions.ID
      ORDER BY business.partner DESC,
      distance,
      propositions.creation_date DESC';
        $query = self::$PDO->prepare($query_def);
        $query->execute([
            ':search' => '%'.$search.'%',
            ':country' => '%'.$country.'%',
            ':field' => '%'.$field.'%',
            ':continuity' => '%'.$continuity.'%',
            ':remuneration' => '%'.$remuneration.'%'
        ]);
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));

    }

    /**
     * Récupération des informations d'une entreprise
     * @param int $business_id ID de l'entreprise
     * @return array
     */
    static public function get_business_info($business_id){
        self::init();
        $query = self::$PDO->prepare('SELECT count(propositions.ID) as nb_prop,business.*
  FROM business,propositions
  WHERE propositions.ID_ent=business.ID
  AND  business.ID=:business_id');
        $query->execute(array('business_id'=>$business_id));
        return DataFormatter::convert_array_to_object($query->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupération des informations de toutes les entreprises
     * @return array
     */
    static public function get_business_list(){
        self::init();
        $query = self::$PDO->prepare('SELECT (SELECT count(propositions.ID) FROM propositions WHERE propositions.ID_ent = business.ID) as nb_prop,business.* FROM business where business.deletion_date IS NULL;');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupération de la liste des proposition d'une entreprise
     * @param int $business_id ID de l'entreprise
     * @return array
     */
    static public function get_business_prop_list($business_id){
        self::init();
        $query = self::$PDO->prepare('SELECT calc_dist(propositions.latitude,propositions.longitude,'.self::$default_lat.','.self::$default_long.') AS distance,propositions.*, business.phone as phone, business.mail as mail,  business.name as name
      FROM business, propositions
      WHERE propositions.ID_ent = business.ID
      AND business.ID = :business_id
      GROUP BY propositions.ID
      ORDER BY distance,
      propositions.creation_date DESC;');
        $query->execute(array(
            ':business_id' => $business_id
        ));
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupération des informations d'une proposition
     * @param int $proposition_id ID de la proposition
     * @return mixed
     */
    static public function get_proposition_info($proposition_id){
        self::init();
        $query = self::$PDO->prepare('SELECT calc_dist(propositions.latitude,propositions.longitude,'.self::$default_lat.','.self::$default_long.') AS distance,propositions.*, business.phone as phone, business.mail as mail, business.name as name
      FROM business, propositions
      WHERE propositions.ID_ent = business.ID
      AND propositions.ID = :proposition_id');
        $query->execute(array(':proposition_id' => $proposition_id));
        return DataFormatter::convert_array_to_object($query->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupération des informations de tout les partenaires
     * @return array
     */
    static public function get_partner_list(){
        self::init();
        $query = self::$PDO->prepare('SELECT count(propositions.ID) as nb_prop,business.*
    FROM business,propositions
    WHERE business.partner = TRUE AND business.deletion_date IS NULL
    GROUP BY business.ID;');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupération des domaines d'activité d'une proposition
     * @param int $proposition_id ID de proposition
     * @return array
     */
    static public function get_prop_fields($proposition_id){
        self::init();
        $query = self::$PDO->prepare('SELECT fields.* FROM fields, linkfields WHERE linkfields.id_field=fields.ID AND linkfields.id_prop=:proposition_id AND linkfields.deletion_date IS NULL');
        $query->execute(array(':proposition_id'=>$proposition_id));
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupération des domaines d'activité d'une entreprise
     * @param int $business_id ID de l'entreprise
     * @return array
     */
    static public function get_business_fields($business_id){
        self::init();
        $query = self::$PDO->prepare('SELECT fields.* FROM fields, linkfields, propositions WHERE linkfields.id_field=fields.ID AND linkfields.id_prop=propositions.ID AND propositions.ID_ent=:business_id AND linkfields.deletion_date IS NULL AND propositions.deletion_date IS NULL GROUP BY fields.ID');
        $query->execute(array(':business_id'=>$business_id));
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupération des possibilités de poursuite d'une proposition
     * @param int $proposition_id ID de la proposition
     * @return array
     */
    static public function get_prop_continuities($proposition_id){
        self::init();
        $query = self::$PDO->prepare('SELECT continuities.* FROM continuities, linkcontinuities WHERE linkcontinuities.ID_cont=continuities.id AND linkcontinuities.ID_prop=:proposition_id AND linkcontinuities.deletion_date IS NULL');
        $query->execute(array(':proposition_id' => $proposition_id));
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupération des possibilités de poursuite d'une entreprise
     * @param int $business_id ID de l'entreprise
     * @return array
     */
    static public function get_business_continuities($business_id){
        self::init();
        $query = self::$PDO->prepare('SELECT continuities.* FROM continuities, linkcontinuities, propositions WHERE linkcontinuities.ID_cont=continuities.id AND linkcontinuities.ID_prop = propositions.ID AND propositions.ID_ent=:business_id AND linkcontinuities.deletion_date IS NULL AND propositions.deletion_date IS NULL GROUP BY continuities.id');
        $query->execute(array(':business_id' => $business_id));
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupére la liste des poursuites possibles dans les entreprises
     * @return \stdClass
     */
    static public function get_continuities_list(){
        self::init();
        $query = self::$PDO->prepare('SELECT * FROM continuities');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupére la liste des domaines d'activité disponible
     * @return \stdClass
     */
    static public function get_fields_list(){
        self::init();
        $query = self::$PDO->prepare('SELECT * FROM fields');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    //SPRINT 2

    /**
     * Récupére le classement des entreprises
     * @return \stdClass
     */
    static public function get_business_ranking(){
        self::init();
        $query = self::$PDO->prepare('
SELECT DISTINCT table2.csop as countStudent,business.* FROM business, (SELECT *,count_student_on_prop(ID) as csop FROM propositions WHERE propositions.deletion_date IS NULL) as table2
WHERE table2.ID_ent = business.ID AND business.deletion_date IS NULL
ORDER BY table2.csop DESC;');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupére l'évolution du nombre de stages
     * @return \stdClass
     */
    static public function get_internship_evolution(){
        self::init();
        $query = self::$PDO->prepare('
SELECT count(linkstudentprop.ID) as nbStudent, DATE(linkstudentprop.creation_date) as date FROM linkstudentprop
WHERE deletion_date IS NULL
GROUP BY DATE(linkstudentprop.creation_date)
ORDER BY linkstudentprop.creation_date;');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupére les informations générales sur les stages
     * @return \stdClass
     */
    static public function get_general_info(){
        self::init();
        $query = self::$PDO->prepare('SELECT
  (SELECT count(linkstudentprop.ID) FROM linkstudentprop WHERE deletion_date IS NULL) as nbTrainee,
  (SELECT count(propositions.ID) FROM propositions WHERE deletion_date IS NULL) as nbPropositions,
  (SELECT count(business.ID) FROM business WHERE deletion_date IS NULL) as nbBusiness,
  (SELECT count(linkstudentprop.ID) FROM linkstudentprop WHERE deletion_date IS NULL)/(SELECT count(students.ID) FROM students WHERE deletion_date IS NULL) * 100 as percentOfFindJob;');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC)[0]);
    }

    /**
     * Récupére la liste des étudiant ne possedant pas de stage
     * @return \stdClass
     */
    static public function get_student_list_without_internship(){
        self::init();
        $query = self::$PDO->prepare('
SELECT * FROM students
WHERE students.ID NOT IN (SELECT ID_student FROM linkstudentprop) AND deletion_date IS NULL;');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupére la liste des étudiants
     * @return \stdClass
     */
    static public function get_student_list(){
        self::init();
        $query = self::$PDO->prepare('
SELECT students.*, groups.label as "group" FROM students,groups
WHERE students.ID_group = groups.ID AND students.deletion_date IS NULL;');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupére les informations de l'étudiant
     * @param $ID int
     * @return \stdClass
     */
    static public function get_student_info($ID){
        self::init();
        $query = self::$PDO->prepare('
SELECT * FROM students,groups WHERE students.ID = :student;');
        $query->execute([':student'=>$ID]);
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC)[0]);
    }

    /**
     * Récupére la liste des groupes d'étudiants
     * @return \stdClass
     */
    static public function get_group_list(){
        self::init();
        $query = self::$PDO->prepare('SELECT * FROM groups;');
        $query->execute();
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Récupére les informations d'un utilisateur
     * @param $ID int
     * @return \stdClass
     */
    static public function get_user_info($ID){
        self::init();
        $query = self::$PDO->prepare('SELECT * FROM users WHERE ID = :ID;');
        $query->execute([':ID' => $ID]);
        return DataFormatter::convert_array_to_object($query->fetchAll(\PDO::FETCH_ASSOC)[0]);
    }

    /**
     * Ajoute un étudiant
     * @param $ID int
     * @param $ID_group int
     * @param $name string
     * @param $fname string
     * @param $email string
     * @param $phone string
     * @param $INE string
     * @param $address string
     * @param $zip_code string
     * @param $city string
     * @param $country string
     * @param $informations string
     * @param $birth_date string
     * @return bool
     * @throws \Exception
     */
    static public function add_student($ID, $ID_group, $name, $fname, $email, $phone, $INE, $address, $zip_code, $city, $country, $informations, $birth_date){
        self::init();
        if(boolval(count(self::$PDO->query('SELECT * FROM students WHERE ID='.$ID)->fetchAll())))   throw new \Exception('L\'utilisateur existe déjà');
        $query = self::$PDO->prepare('
INSERT INTO students(ID,ID_group, name, fname, email, phone, INE, address, zip_code, city, country, informations, creation_date, birth_date)
VALUES (:ID,:ID_group, :name, :fname, :email, :phone, :INE, :address, :zip_code, :city, :country, :informations, NOW(), :birth_date);');
        return $query->execute([
            ':ID'=> $ID,
            ':ID_group'=> $ID_group,
            ':name'=> $name,
            ':fname'=> $fname,
            ':email'=> $email,
            ':phone'=> $phone,
            ':INE'=> $INE,
            ':address'=> $address,
            ':zip_code'=> $zip_code,
            ':city'=> $city,
            ':country'=> $country,
            ':informations'=> $informations,
            ':birth_date'=> $birth_date
        ]);
    }

    /**
     * Ajoute une entreprise
     * @param $name string
     * @param $address string
     * @param $zip_code string
     * @param $city string
     * @param $country string
     * @param $description string
     * @param $director_name string
     * @param $phone string
     * @param $mail string
     * @param $siren string
     * @param $partner bool
     * @return bool
     */
    static public function add_business($name, $address, $zip_code, $city, $country, $description, $director_name, $phone, $mail, $siren, $partner){
        self::init();
        $query = self::$PDO->prepare('
INSERT INTO business(name, address, zip_code, city, country, description, director_name, phone, mail, siren, partner, creation_date)
    VALUES (:name, :address, :zip_code, :city, :country, :description, :director_name, :phone, :mail, :siren, :partner, NOW());');
        return $query->execute([
            ':name' => $name,
            ':address' => $address,
            ':zip_code' => $zip_code,
            ':city' => $city,
            ':country' => $country,
            ':description' => $description,
            ':director_name' => $director_name,
            ':phone' => $phone,
            ':mail' => $mail,
            ':siren' => $siren,
            ':partner' => boolval($partner)
        ]);
    }

    /**
     * Ajoute une proposition à une entreprise
     * @param $ID_ent int
     * @param $label string
     * @param $adress string
     * @param $zip_code string
     * @param $city string
     * @param $country string
     * @param $description string
     * @param $duration string
     * @param $skills string
     * @param $remuneration int [0,1,2]
     * @return bool
     * @throws \Exception
     */
    static public function add_proposition($ID_ent, $label, $adress, $zip_code, $city, $country, $description, $duration, $skills, $remuneration){
        self::init();
        try{
            $GPS_coordinates = Map::get_latitude_longitude($adress,$city,$zip_code,$country);
        }catch (\Exception $e){
            throw $e;
        }
        $query = self::$PDO->prepare('
INSERT INTO propositions(ID_ent, label, adress, zip_code, city, country, latitude, longitude, description, duration, skills, remuneration, creation_date)
    VALUES (:ID_ent, :label, :adress, :zip_code, :city, :country, :latitude, :longitude, :description, :duration, :skills, :remuneration, NOW());');
        return $query->execute([
            ':ID_ent' => $ID_ent,
            ':label' => $label,
            ':adress' => $adress,
            ':zip_code' => $zip_code,
            ':city' => $city,
            ':country' => $country,
            ':latitude' => $GPS_coordinates->latitude,
            ':longitude' => $GPS_coordinates->longitude,
            ':description' => $description,
            ':duration' => $duration,
            ':skills' => $skills,
            ':remuneration' => $remuneration
        ]);
    }

    /**
     * Ajoute un domaine d'activité
     * @param $label string
     * @return bool
     */
    static public function add_field($label){
        self::init();
        $query = self::$PDO->prepare('INSERT INTO fields(label) VALUES (:label);');
        return $query->execute([':label'=> $label]);
    }

    /**
     * Ajoute une poursuite possible
     * @param $label string
     * @return bool
     */
    static public function add_continuity($label){
        self::init();
        $query = self::$PDO->prepare('INSERT INTO continuities(label) VALUES (:label);');
        return $query->execute([':label'=> $label]);
    }

    /**
     * Ajoute un groupe
     * @param $label string
     * @return bool
     */
    static public function add_group($label){
        self::init();
        $query = self::$PDO->prepare('INSERT INTO groups(label) VALUES (:label);');
        return $query->execute([':label'=> $label]);
    }

    /**
     * Lie un étudiant à un stage
     * @param $ID_student int
     * @param $ID_prop int
     * @return bool
     */
    static public function link_student_to_proposition($ID_student,$ID_prop){
        self::init();
        $query = self::$PDO->prepare('INSERT INTO linkstudentprop(ID_student, ID_prop, creation_date) VALUES (:ID_student,:ID_prop,NOW());');
        return $query->execute([':ID_student'=> $ID_student, ':ID_prop' => $ID_prop]);
    }

    /**
     * Lie une proposition à un domaine d'activité
     * @param $id_prop int
     * @param $id_field int
     * @return bool
     */
    static public function link_proposition_to_field($id_prop,$id_field){
        self::init();
        $query = self::$PDO->prepare('INTO linkfields(id_prop, id_field) VALUES (:id_prop, :id_field);');
        return $query->execute([':id_prop'=> $id_prop, ':id_field' => $id_field]);
    }

    /**
     * Lie une proposition à une poursuite possible
     * @param $id_prop int
     * @param $id_continuity int
     * @return bool
     */
    static public function link_proposition_to_continuity($id_prop,$id_continuity){
        self::init();
        $query = self::$PDO->prepare('INSERT INTO linkcontinuities(ID_prop, ID_cont) VALUES (:ID_prop, :ID_cont);');
        return $query->execute([':ID_prop'=> $id_prop, ':ID_cont' => $id_continuity]);
    }

    /**
     * Modifie les informations d'un étudiant
     * @param $lastID int
     * @param $new_ID int
     * @param $ID_group int
     * @param $name string
     * @param $fname string
     * @param $email string
     * @param $phone string
     * @param $INE string
     * @param $address string
     * @param $zip_code string
     * @param $city string
     * @param $country string
     * @param $informations string
     * @param $birth_date string
     * @return bool
     */
    static public function edit_student($lastID, $new_ID, $ID_group, $name, $fname, $email, $phone, $INE, $address, $zip_code, $city, $country, $informations, $birth_date){
        self::init();
        $query = self::$PDO->prepare('
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
  WHERE ID = :last_id;');
        return $query->execute([
            ':ID'=> $new_ID,
            ':last_id'=> $lastID,
            ':ID_group'=> $ID_group,
            ':name'=> $name,
            ':fname'=> $fname,
            ':email'=> $email,
            ':phone'=> $phone,
            ':INE'=> $INE,
            ':address'=> $address,
            ':zip_code'=> $zip_code,
            ':city'=> $city,
            ':country'=> $country,
            ':informations'=> $informations,
            ':birth_date'=> $birth_date]);
    }

    /**
     * Supprime un étudiant
     * @param $ID int
     * @return bool
     */
    static public function delete_student($ID){
        self::init();
        $query = self::$PDO->prepare('
UPDATE students SET deletion_date = NOW() WHERE ID = :ID;
UPDATE linkstudentprop SET deletion_date = NOW() WHERE ID_student = :ID;');
        return $query->execute([':ID'=> $ID]);
    }

    /**
     * Modifie les informations d'une entreprise
     * @param $ID int
     * @param $name string
     * @param $address string
     * @param $zip_code string
     * @param $city string
     * @param $country string
     * @param $description string
     * @param $director_name string
     * @param $phone string
     * @param $mail string
     * @param $siren string
     * @param $partner true
     * @return bool
     */
    static public function edit_business($ID, $name, $address, $zip_code, $city, $country, $description, $director_name, $phone, $mail, $siren, $partner){
        self::init();
        $query = self::$PDO->prepare('
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
  WHERE ID = :ID;');
        return $query->execute([
            ':ID' => $ID,
            ':name' => $name,
            ':address' => $address,
            ':zip_code' => $zip_code,
            ':city' => $city,
            ':country' => $country,
            ':description' => $description,
            ':director_name' => $director_name,
            ':phone' => $phone,
            ':mail' => $mail,
            ':siren' => $siren,
            ':partner' => boolval($partner)
        ]);
    }

    /**
     * Supprime une entreprise
     * @param $ID int
     * @return bool
     */
    static public function delete_business($ID){
        self::init();
        $query = self::$PDO->prepare('
UPDATE business SET deletion_date = NOW() WHERE ID = :ID;
UPDATE propositions SET deletion_date = NOW() WHERE ID_ent = :ID;
UPDATE linkfields SET deletion_date = NOW() WHERE id_prop IN (SELECT ID FROM propositions WHERE ID_ent = :ID);
UPDATE linkcontinuities SET deletion_date = NOW() WHERE id_prop IN (SELECT ID FROM propositions WHERE ID_ent = :ID);');
        return $query->execute([':ID'=> $ID]);
    }

    /**
     * Modifie les informations d'une proposition
     * @param $ID int
     * @param $ID_ent int
     * @param $label string
     * @param $adress string
     * @param $zip_code string
     * @param $city string
     * @param $country string
     * @param $description string
     * @param $duration string
     * @param $skills string
     * @param $remuneration int
     * @return bool
     * @throws \Exception
     */
    static public function edit_proposition($ID, $ID_ent, $label, $adress, $zip_code, $city, $country, $description, $duration, $skills, $remuneration){
        self::init();
        try{
            $GPS_coordinates = Map::get_latitude_longitude($adress,$city,$zip_code,$country);
        }catch (\Exception $e){
            throw $e;
        }
        $query = self::$PDO->prepare('
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
  WHERE ID = :ID;');
        return $query->execute([
            ':ID' => $ID,
            ':ID_ent' => $ID_ent,
            ':label' => $label,
            ':adress' => $adress,
            ':zip_code' => $zip_code,
            ':city' => $city,
            ':country' => $country,
            ':latitude' => $GPS_coordinates->latitude,
            ':longitude' => $GPS_coordinates->longitude,
            ':description' => $description,
            ':duration' => $duration,
            ':skills' => $skills,
            ':remuneration' => $remuneration
        ]);
    }

    /**
     * Supprime une proposition
     * @param $ID int
     * @return bool
     */
    static public function delete_proposition($ID){
        self::init();
        $query = self::$PDO->prepare('
UPDATE propositions SET deletion_date = NOW() WHERE ID = :ID;
UPDATE linkfields SET deletion_date = NOW() WHERE id_prop = :ID;
UPDATE linkcontinuities SET deletion_date = NOW() WHERE id_prop = :ID;');
        return $query->execute([':ID'=> $ID]);
    }

    /**
     * Modifie le nom d'un domaine d'activité
     * @param $ID int
     * @param $label string
     * @return bool
     */
    static public function edit_field($ID,$label){
        self::init();
        $query = self::$PDO->prepare('UPDATE fields SET label = :label WHERE ID = :ID;');
        return $query->execute([':label'=> $label, ':ID' => $ID]);
    }

    /**
     * Modifie le nom d'une poursuite
     * @param $ID
     * @param $label
     * @return bool
     */
    static public function edit_continuities($ID,$label){
        self::init();
        $query = self::$PDO->prepare('UPDATE continuities SET label = :label WHERE ID = :ID;');
        return $query->execute([':label'=> $label, ':ID' => $ID]);
    }

    /**
     * Retire un lien dans linkfields
     * @param $ID int
     * @return bool
     */
    static public function unlink_proposition_to_field($ID){
        self::init();
        $query = self::$PDO->prepare('UPDATE linkfields SET deletion_date = NOW() WHERE id = :ID;');
        return $query->execute([':ID' => $ID]);
    }

    /**
     * Retire un lien dans linkcontinuities
     * @param $ID int
     * @return bool
     */
    static public function unlink_proposition_to_continuity($ID){
        self::init();
        $query = self::$PDO->prepare('UPDATE linkcontinuities SET deletion_date = NOW() WHERE id = :ID;');
        return $query->execute([':ID' => $ID]);
    }

    /**
     * Supprime un étudiant d'un stage
     * @param $ID_student int
     * @return bool
     */
    static public function unlink_student_to_internship($ID_student){
        self::init();
        $query = self::$PDO->prepare('UPDATE linkstudentprop SET deletion_date = NOW() WHERE ID_student = :ID;');
        return $query->execute([':ID_student' => $ID_student]);
    }

    /**
     * Supprime un domaine d'activité et tout ses liens
     * @param $ID int
     * @return bool
     */
    static public function delete_field($ID){
        self::init();
        $query = self::$PDO->prepare('
DELETE FROM fields WHERE ID = :ID;
DELETE FROM linkfields WHERE id_field = :ID;');
        return $query->execute([':ID' => $ID]);
    }

    /**
     * Supprime une poursuite et tout ses liens
     * @param $ID int
     * @return bool
     */
    static public function delete_continuity($ID){
        self::init();
        $query = self::$PDO->prepare('
DELETE FROM continuities WHERE ID = :ID;
DELETE FROM linkcontinuities WHERE id_field = :ID;');
        return $query->execute([':ID' => $ID]);
    }

}