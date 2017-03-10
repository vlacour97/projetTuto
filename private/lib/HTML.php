<?php
/**
 * Created by PhpStorm.
 * User: £ÐØµã®Ð
 * Date: 05/01/2017
 * Time: 16:32
 */

namespace lib;


class HTML
{

    function css($filename)
    {
        $part = explode(".", $filename);
        if( $part[count($part)-1]  != "css" )
            return false;
        if (!filter_var($filename, FILTER_VALIDATE_URL))
        {
            $filename = "public/css/$filename";
            if(!is_file($filename))
                return false;
        }
        return "<link rel = 'stylesheet' type = 'text/css' href='$filename'>";


    }

    function script($filename)
    {
        $part = explode(".", $filename);
        if (!filter_var($filename, FILTER_VALIDATE_URL))
        {
            $filename = "public/js/$filename";
            if(!is_file($filename))
                return false;
        }

        return "<script type='text/javascript' src='$filename'></script>";

    }

    function meta($desc)
    {
        $meta = "";
        foreach( $desc as $key=>$element)
            $meta .= "$key=\"$element\" ";

        return "<meta $meta>";
    }

}