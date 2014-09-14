<?php
// Подключаю все JS из папки js/fullScripts
function include_from($dir, $ext='php'){
$opened_dir = opendir($_SERVER["DOCUMENT_ROOT"].$dir);

while ($element=readdir($opened_dir)){
$fext=substr($element,strlen($ext)*-1);
if(($element!='.') && ($element!='..') && ($fext==$ext)){
echo "<script type='text/javascript' src='$dir/$element'></script>";
}
}
closedir($opened_dir);
}

include_from('/design/js/fullScripts/', 'js');

// Секция конфига сайта для ubuntu
$pfx = array(
  "design" => "/design/"
);
