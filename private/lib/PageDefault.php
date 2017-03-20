<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 08/02/17
 * Time: 23:45
 */

namespace lib;


class PageDefault {

    protected function header(){
        $html = new HTML();
        $config = new \lib\Config();
        echo '<!DOCTYPE html>';
        echo '<html lang="fr">';
        echo '<head>';
        echo $html->meta(['http-equiv'=>'Content-Type', 'content' => 'text/html; charset=utf-8']);
        echo $html->meta(['name'=>'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
        echo $html->meta(['name' => 'viewport']);
        echo '<title>'.$config->getName().'</title>';
        echo '<link rel="icon" type="image/png" href="/public/img/logo.png" />';
        echo $html->css('bootstrap.min.css');
        echo $html->css('font-awesome.min.css');
        echo $html->css('themify-icons.css');
        echo $html->css('owl.carousel.css');
        echo $html->css('price-range.css');
        echo $html->css('style.css');
        echo $html->css('responsive.css');
        echo $html->css('colors.css');
        echo $html->css('settings.css');
        echo $html->script('modernizr.js');
        echo $html->script('jquery-1.10.2.min.js');
        echo $html->script('bootstrap.min.js');
        echo '</head>';
        echo '<body >';
        //preloader
        echo '<div id="preloader"></div>';
        echo '<div class="theme-layout">';

    }

    protected function sidebar(){

    }

    protected function nav(){
        echo '<header class="simple-header for-sticky ">

            <div class="menu">
                <div class="container">
                    <div class="logo">
                        <a href="?nav=home" title="Accueil">
                            <div class="row">
                                <div class="col-md-3">
                                    <i><img src="public/img/logo.png"  height="85" width="85"></i>
                                </div>
                                <div class="col-md-9">
                                    <br>
                                    <span>BusinessHunter</span>
                                    <strong>Recherche de stage</strong>
                                </div>
                            </div>
                        </a>
                    </div><!-- LOGO -->

                    <span class="menu-toggle"><i class="fa fa-bars"></i></span>
                    <nav>
                        <ul>
                            <li>
                                <a href="?nav=home" title="">Accueil</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </header>';
    }

    protected function footer(){
        $html = new HTML();
        $config = new \lib\Config();
        echo '<footer>

            <div class="bottom-line">
                <div class="container">
                    <span>Copyright All Right Reserved 2016 </span>
                    <ul>
                        <li><a title="Accueil" href="?nav=home">ACCUEIL</a></li>
                        <li><a title="Gestion de stage" href="?admin=true&nav=home">GESTION DE STAGE</a></li>
                    </ul>
                </div>
            </div>
            <a href="#" class="scrollToTop"><i class="ti ti-arrow-circle-up"></i></a>
        </footer>

    </div>';
        echo $html->script('owl.carousel.min.js');
        echo $html->script('scrolly.js');
        echo $html->script('price-range.js');
        echo $html->script('script.js');
        echo $html->script('https://maps.googleapis.com/maps/api/js?key='.$config->getApiKeyGmaps().'&callback=initMap');
        echo $html->script('infobox.js');
        echo $html->script('markerclusterer.js');
        echo $html->script('markers-map.js');
        echo '<script>
        google.maps.event.addDomListener(window, "load", speedTest.init);
    </script>


    <script type="text/javascript">
        $(document).ready(function () {
            "use strict";

            $(".carousel-prop").owlCarousel({
                autoplay: true,
                autoplayTimeout: 3000,
                smartSpeed: 2000,
                loop: true,
                dots: true,
                nav: false,
                items: 4,
                itemsCustom: false,
                itemsDesktop: [1199, 3],
                itemsDesktopSmall: [980, 2],
                itemsTablet: [768, 2],
                itemsTabletSmall: false,
                itemsMobile: [479, 1],
                itemsScaleUp: false

            });


            $(".carousel").owlCarousel({
                autoplay: true,
                autoplayTimeout: 3000,
                smartSpeed: 2000,
                loop: false,
                dots: false,
                nav: true,
                margin: 0,
                items: 3
            });

            $(".carousel-client").owlCarousel({
                autoplay: true,
                autoplayTimeout: 3000,
                smartSpeed: 2000,
                loop: false,
                dots: false,
                items: 5,
                itemsCustom: false,
                itemsDesktop: [1199, 5],
                itemsDesktopSmall: [980, 3],
                itemsTablet: [768, 2],
                itemsTabletSmall: false,
                itemsMobile: [479, 1],
                itemsScaleUp: false
            });

        });
    </script>';
    echo '</body>';
    echo '</html>';

    }

} 