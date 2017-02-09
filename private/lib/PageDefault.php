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
        echo '<!DOCTYPE html>';
        echo '<head>';
        echo $html->meta(['http-equiv'=>'Content-Type', 'content' => 'text/html; charset=utf-8']);
        echo $html->meta(['name'=>'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
        echo $html->meta(['name' => 'viewport']);
        echo '<title>Kwitara - Bootstrap Real Estate template </title>';
        echo $html->css('bootstrap.min.css');
        echo $html->css('font-awesome.min.css');
        echo $html->css('themify-icons.css');
        echo $html->css('owl.carousel.css');
        echo $html->css('price-range.css');
        echo $html->css('style.css');
        echo $html->css('responsive.css');
        echo $html->css('color.css');
        echo $html->css('settings.css');
        echo '</head>';
    }

    protected function nav(){
        echo "<div class='nav'>NavigationTest</div>";
    }

    protected function footer(){
        $html = new HTML();
        echo $html->script('modernizr.js');
        echo $html->script('jquery-1.10.2.min.js');
        echo $html->script('bootstrap.min.js');
        echo $html->script('owl.carousel.min.js');
        echo $html->script('html5lightbox.js');
        echo $html->script('scrolly.js');
        echo $html->script('price-range.js');
        echo $html->script('script.js');

        echo $html->script('https://maps.googleapis.com/maps/api/js');
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