<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 22/01/17
 * Time: 18:00
 */


include '../../init.php';

echo '<img src="'.\lib\File::get_img_path(\lib\File::BUSINESS_LOGO,2).'">';
echo '<pre>';
var_dump(\lib\File::get_img_path(\lib\File::BUSINESS_LOGO,2));

var_dump($_FILES);

try{
    if(isset($_FILES['file'])) lib\File::upload('../../..'.\lib\File::BUSINESS_LOGO,$_FILES['file'],null,'17.jpg',true);
}catch(Exception $e)
{
    if($e->getCode() == 1)
        echo "Warning: ".$e->getMessage();
    if($e->getCode() == 2)
        echo "Danger: ".$e->getMessage();
}


echo '<form method="post" action=# enctype="multipart/form-data"><input type="file" name="file" ><button type="submit">Envoyer</button></form>';