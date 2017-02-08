<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 22/01/17
 * Time: 17:50
 */

namespace lib;


class File {

    const BUSINESS_LOGO = '/private/data/business/logo/';

    static public function get_img_path($path, $ID){
        $file_path = $path.$ID.'.jpg';
        if(!is_file(ABS_PATH.$file_path))
            $file_path = $path.'default.jpg';
        return $file_path;
    }

} 