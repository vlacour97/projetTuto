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
        echo "<style>.nav{  display: block; width: 100%; height: 2em; background: #232323; color: #FFFFFF;  }</style>";
    }

    protected function nav(){
        echo "<div class='nav'>NavigationTest</div>";
    }

    protected function footer(){
        echo "<div class='nav'>FooterTest</div>";
    }

} 