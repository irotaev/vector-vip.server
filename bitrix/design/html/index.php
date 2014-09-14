<?php
require 'static/index.html';

// Подключаю все JS из папки js/fullScripts
function include_from($dir, $ext='php'){
$opened_dir = opendir($dir);

while ($element=readdir($opened_dir)){
$fext=substr($element,strlen($ext)*-1);
if(($element!='.') && ($element!='..') && ($fext==$ext)){
echo "<script type='text/javascript' src='$dir/$element'></script>";
}
}
closedir($opened_dir);
}

include_from('js/fullScripts', 'js');