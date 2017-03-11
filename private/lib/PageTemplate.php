<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 25/01/17
 * Time: 13:58
 */

namespace lib;

use controllers;

/**
 * Class PageTemplateStatement
 * @package lib
 * @author Valentin Lacour
 */
class PageTemplateStatement{
    /**
     * @var \stdClass
     */
    public $get;
    /**
     * @var \stdClass
     */
    public $post;
    /**
     * @var \stdClass
     */
    public $files;
    /**
     * @var \stdClass
     */
    public $cookie;
    /**
     * @var \stdClass
     */
    public $session;
    /**
     * @var \stdClass
     */
    public $env;
    /**
     * @var \stdClass
     */
    public $request;
    /**
     * @var \stdClass
     */
    public $server;
    /**
     * @var \stdClass
     */
    public $config;
    /**
     * @var string
     */
    public $site_name;
    /**
     * @var string
     */
    public $page_name;
    /**
     * @var string
     */
    public $part_name;

    /**
     * Initialisation des variables d'environnement
     */
    function __construct(){
        $this->get = DataFormatter::convert_array_to_object($_GET);
        $this->post = DataFormatter::convert_array_to_object($_POST);
        $this->files = DataFormatter::convert_array_to_object($_FILES);
        $this->cookie = DataFormatter::convert_array_to_object($_COOKIE);
        $this->session = DataFormatter::convert_array_to_object($_SESSION);
        $this->env = DataFormatter::convert_array_to_object($_ENV);
        $this->request = DataFormatter::convert_array_to_object($_REQUEST);
        $this->server = DataFormatter::convert_array_to_object($_SERVER);

        $page_name_temp = $_GET[PageTemplate::navigation_tag];
        is_null($page_name_temp) && $page_name_temp = PageTemplate::default_navigation_name;
        $part_name_temp = $_GET[PageTemplate::part_tag];
        is_null($part_name_temp) && $part_name_temp = PageTemplate::default_part_name;
        $is_admin_temp = $_GET[PageTemplate::admin_tag] == 'true' || (!$_GET[PageTemplate::admin_tag] && $is_admin_temp = false);

        $this->config = new Config();

        $this->site_name = $this->config->getName();
        $this->page_name = $page_name_temp;
        $this->part_name =  $part_name_temp;
        $this->is_admin =  $is_admin_temp;
    }
}

/**
 * Class PageTemplate
 * @package lib
 * @author Valentin Lacour
 */
class PageTemplate extends PageDefault{

    /**
     * @var \stdClass
     */
    protected  $var;
    /**
     * @var PageTemplateStatement
     */
    protected $global;

    const navigation_tag = 'nav';
    const default_navigation_name = 'home';
    const part_tag = 'part';
    const default_part_name = 'index';
    const admin_tag = 'admin';
    const connexion_tag = 'connexion_id';
    const components_path = '/private/components/';

    /**
     * Initialisation des variables d'environnement
     */
    public function __construct(){
        $this->global = new PageTemplateStatement();
        $this->var->global = $this->global;
    }

    /**
     * Initialisation du template
     * @throws \Exception
     */
    public function init(){
        //Initialisation des variables pour le traitement
        $page_name = $this->global->page_name;
        $part_name = $this->global->part_name;
        $is_admin = $this->global->is_admin;

        if($is_admin){
            $view_path = ABS_PATH.'/public/views/admin/'.$page_name.'/'.$part_name.'.html';
            $controller_name = 'controllers\admin\Page'.ucfirst($page_name);
        }else{
            $view_path = ABS_PATH.'/public/views/'.$page_name.'/'.$part_name.'.html';
            $controller_name = 'controllers\Page'.ucfirst($page_name);
        }
        $part_name = "_".$part_name;

        //Vérification et chargement de la vue
        if(!is_file($view_path) && !$this->_isAjax())
            $this->_redirect($this::default_navigation_name,$this::default_part_name,false,$is_admin);

        // Création du controller et chargement de celui-ci
        if(class_exists($controller_name)){
            $controller_object = new $controller_name();
            if(method_exists($controller_object,$part_name)){
                $controller_object->$part_name();
                $var = $controller_object->var;
                $g_page_name = $this->global->page_name;
                $g_part_name = $this->global->part_name;
            }
        }

        //Chargement des composants de developpement
        if($this->global->config->getDebugMod() && !$this->_isAjax()) $this->showDebugger($var);

        //Changement de design pour la partie admin
        if($is_admin) $page_default = new PageDefaultAdmin(); else $page_default = $controller_object;

        //Inclusion du content selon les cas échéants
        if(!$this->_isAjax()) $controller_object->include_content($view_path,$page_default,$this->var); else  include($view_path);
    }

    /**
     * Permet d'inclure le contenu dans la page
     * @param $view_path string
     * @param $controller_object \lib\PageDefault
     */
    protected function include_content($view_path,$controller_object,$var){
        $controller_object->header($var);
        $controller_object->sidebar($var);
        $controller_object->nav($var);
        include($view_path);
        $controller_object->footer($var);
    }

    /**
     * Affichage du debugger
     * @param \stdClass $data Variables d'environnement
     */
    private function showDebugger($data){
        $debug_gabarit = file_get_contents(ABS_PATH.$this::components_path.'debugger.html');
        $debug_result = "";
        if(!is_null($data))
            foreach($data as $key=>$value){
                if($key != 'global'){
                    ob_start();
                    var_dump($value);
                    $debug_result .= '<li><b> $var->'.$key.'</b> => '.ob_get_clean().'</li>';
                }
            }
        foreach($this->global as $key=>$value){
            ob_start();
            var_dump($value);
            $debug_result .= '<li><b> $var->global->'.$key.'</b> => '.ob_get_clean().'</li>';
        }
        $debug_gabarit = str_replace('{debug-data}',$debug_result,$debug_gabarit);
        echo $debug_gabarit;
    }

    /**
     * Redirection de page
     * @param null|string $part
     * @param null|string $page
     * @param bool $saveData
     */
    protected function _redirect($page = null,$part = null,$saveData = false, $is_admin = false){
        is_null($part) && $part = $this->global->part_name;
        is_null($page) && $page = $this->global->page_name;
        $link = 'index.php?';
        $is_admin && $link .= $this::admin_tag.'=true&';
        $saveData && $link .= $this->_saveDataInLink(true).'&';
        $link .= $this::navigation_tag.'='.$page.'&'.$this::part_tag.'='.$part;
        header("Location: $link");
    }

    /**
     * Génération de bout de lien afin de récupérer toutes les variables get
     * @param bool $except_nav_part
     * @return string
     */
    protected function _saveDataInLink($except_nav_part = false){
        $response = '';
        foreach($this->global->get as $key=>$value)
            if($key != $this::navigation_tag && $key != $this::part_tag && $except_nav_part)
                $response .= $key.'='.$value.'&';
        substr($response,0,-1);
        return $response;
    }

    protected function _setLink($label,$data = array(),$options = array()){
        is_null($data['nav']) && $data['nav'] = $this->global->page_name;
        is_null($data['part']) && $data['part'] = $this->global->part_name;


        $nav = $data['nav'];
        $part = $data['part'];

        unset($data['nav']);
        unset($data['part']);

        $data[$this::navigation_tag ] = $nav;
        $data[$this::part_tag ] = $part;

        $response = 'index.php?';
        foreach($data as $key=>$value)
                $response .= $key.'='.$value.'&';
        substr($response,0,-1);

        $options_text = '';
        foreach($options as $key=>$value){
            $options_text .= "$key='$value' ";
        }
        return '<a href="'.$response.'" '.$options_text.'>'.$label.'</a>';
    }

    /**
     * Determine si une page est chargé ou non
     * @param string $part
     * @param null|string $page
     * @return bool
     */
    protected function _isLoaded($part,$page = null){
        is_null($page) && $page = $this->global->page_name;
        return $part == $this->global->part_name && $this->global->page_name;
    }

    /**
     * Determine si l'utilisateur est connecté
     * @return bool
     */
    protected function _isConnected(){
        if(!isset($_SESSION[self::connexion_tag])) return false;
        $response = BDD::get_user_info(Crypt::decrypt($_SESSION[self::connexion_tag]));
        if(!isset($response->deletion_date) && $response->deletion_date == ""){
            unset($_SESSION[self::connexion_tag]);
            return false;
        }
        return true;
    }

    /**
     * Determine si une connexion est passé par Ajax ou non
     * @return bool
     */
    protected function _isAjax(){
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
} 