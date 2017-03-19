<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 08/02/17
 * Time: 23:45
 */

namespace lib;


class PageDefaultAdmin {

    public function header(){
        $html = new HTML();
        $config = new \lib\Config();
        echo '<!DOCTYPE html>';
        echo '<html lang="fr">';
        echo '<head>';
        echo $html->meta(['http-equiv'=>'Content-Type', 'content' => 'text/html; charset=utf-8']);
        echo $html->meta(['name'=>'viewport', 'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0']);
        echo $html->meta(['name' => 'viewport', 'content' => 'width=device-width']);
        echo '<title>'.$config->getName().' - Administration</title>';
        echo $html->css('bootstrap.min.css');
        echo $html->css('material-dashboard.css');
        echo $html->css('font-awesome.min.css');
        echo $html->css('https://fonts.googleapis.com/css?family=Roboto:300,400,500,700%7CMaterial+Icons');
        echo $html->script('jquery-3.1.0.min.js');
        echo '</head>';
        echo '<body >';
        echo '<div class="wrapper">';

    }

    public function sidebar($var){
        $config = new \lib\Config();
        $user_info = BDD::get_user_info(Crypt::decrypt($var->global->session->{PageTemplate::connexion_tag}));
        $nav_parts = link_parameters('app/pages_admin_data');
        $nav = $_GET[PageTemplate::navigation_tag];
        echo '<div class="sidebar" data-active-color="blue" data-background-color="black" data-image="/public/img/sidebar_admin.jpg">';
        /**
        * Tip 1: You can change the color of active element of the sidebar using: data-active-color="purple | blue | green | orange | red | rose"
        * Tip 2: you can also add an image using data-image tag
        * Tip 3: you can change the color of the sidebar with data-background-color="white | black"
        */
        echo '<div class="logo"><a href="'.$_SERVER['HTTP_HOST'].'" class="simple-text">'.$config->getName().'</a></div>';
        echo '<div class="logo logo-mini"><a href="'.$_SERVER['HTTP_HOST'].'" class="simple-text">'.Text::getInitials($config->getName()).'</a></div>';
        echo'<div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="'.File::get_img_path(File::USER_AVATAR,$user_info->ID).'" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            '.$user_info->fname.' '.$user_info->name.'
                            <b class="caret"></b>
                        </a>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="index.php?admin=true&nav=current_user&part=index">Mon Profil</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>';
        echo '<ul class="nav">';
        foreach($nav_parts as $content){
            if($nav == $content['id']) $active = "class='active'"; else $active = "";
            echo '<li '.$active.'>
                        <a href="index.php?admin=true&nav='.$content['id'].'">
                            <i class="material-icons">'.$content['icon'].'</i>
                            <p>'.$content['name'].'</p>
                        </a>
                    </li>';
        }
        echo '</ul>
            </div>
        </div>';
    }

    public function nav($var){
        echo '<div class="main-panel"><nav class="navbar navbar-transparent navbar-absolute">
                <div class="container-fluid">
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                            <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                            <i class="material-icons visible-on-sidebar-mini">view_list</i>
                        </button>
                    </div>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"> '.$var->titlePage.' </a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="index.php?admin=true&nav=home&part=index">
                                    <i class="material-icons">dashboard</i>
                                    <p class="hidden-lg hidden-md">Tableau de Bord</p>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">notifications</i>
                                    <span class="notification">5</span>
                                    <p class="hidden-lg hidden-md">
                                        Notifications
                                        <b class="caret"></b>
                                    </p>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">Un nouvel étudiant à trouvé un stage</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="index.php?admin=true&nav=current_user&part=index">
                                    <i class="material-icons">person</i>
                                    <p class="hidden-lg hidden-md">Mon Profil</p>
                                </a>
                            </li>
                            <li class="separator hidden-lg hidden-md"></li>
                        </ul>
                        <form class="navbar-form navbar-right" role="search">
                            <div class="form-group form-search is-empty">
                                <input type="text" class="form-control" placeholder="Rechercher">
                                <span class="material-input"></span>
                            </div>
                            <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                <i class="material-icons">search</i>
                                <div class="ripple-container"></div>
                            </button>
                        </form>
                    </div>
                </div>
            </nav><div class="content">
    <div class="container-fluid">';
    }

    public function footer(){
        $html = new HTML();
        $config = new \lib\Config();
        echo '</div>
</div><footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul>
							<li>
								<a href="index.php">
									Accueil
								</a>
							</li>
						</ul>
					</nav>
					<p class="copyright pull-right">
						&copy; <script>document.write(new Date().getFullYear())</script> Fait avec <i class="fa fa-heart"></i> par notre groupe de projet Tutoré
					</p>
				</div>
			</footer>
			</div>
    </div>';
        echo '</body>';
        echo $html->script('jquery-ui.min.js');
        echo $html->script('bootstrap.min.js');
        echo $html->script('material.min.js');
        echo $html->script('perfect-scrollbar.jquery.min.js');
        echo $html->script('jquery.validate.min.js');
        echo $html->script('moment.min.js');
        echo $html->script('chartist.min.js');
        echo $html->script('jquery.bootstrap-wizard.js');
        echo $html->script('bootstrap-notify.js');
        echo $html->script('jquery.sharrre.js');
        echo $html->script('bootstrap-datetimepicker.js');
        echo $html->script('jquery-jvectormap.js');
        echo $html->script('nouislider.min.js');
        echo $html->script('https://maps.googleapis.com/maps/api/js?key='.$config->getApiKeyGmaps());
        echo $html->script('jquery.select-bootstrap.js');
        echo $html->script('sweetalert2.js');
        echo $html->script('jasny-bootstrap.min.js');
        echo $html->script('fullcalendar.min.js');
        echo $html->script('jquery.tagsinput.js');
        echo $html->script('material-dashboard.js');
        echo $html->script('jquery.datatables.js');
        echo '</html>';

    }

} 