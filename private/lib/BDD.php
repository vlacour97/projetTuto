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
      FROM propositions,business,fields,continuities,linkcontinuities,linkfields
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
        $query = self::$PDO->prepare('SELECT count(propositions.ID) as nb_prop,business.* FROM business,propositions where business.ID=propositions.ID_ent GROUP BY business.ID');
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
    WHERE business.partner = TRUE
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
        $query = self::$PDO->prepare('SELECT fields.* FROM fields, linkfields WHERE linkfields.id_field=fields.ID AND linkfields.id_prop=:proposition_id');
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
        $query = self::$PDO->prepare('SELECT fields.* FROM fields, linkfields, propositions WHERE linkfields.id_field=fields.ID AND linkfields.id_prop=propositions.ID AND propositions.ID_ent=:business_id GROUP BY fields.ID');
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
        $query = self::$PDO->prepare('SELECT continuities.* FROM continuities, linkcontinuities WHERE linkcontinuities.ID_cont=continuities.id AND linkcontinuities.ID_prop=:proposition_id');
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
        $query = self::$PDO->prepare('SELECT continuities.* FROM continuities, linkcontinuities, propositions WHERE linkcontinuities.ID_cont=continuities.id AND linkcontinuities.ID_prop = propositions.ID AND propositions.ID_ent=:business_id GROUP BY continuities.id');
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

}