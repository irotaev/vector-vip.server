<?php
$projectDir = realpath(__DIR__ . '/../');

// ������ sass
if (!file_exists($projectDir.'/design/css')) {
    mkdir($projectDir.'/design/css');
}
exec('sass -f '.$projectDir.'/design/scss/screen.scss '.$projectDir.'/design/css/screen.css');
//exec('sass -f '.$projectDir.'/design/sass/print.scss '.$projectDir.'/design/css/print.css');

$dir = $projectDir.'/design/js/make';
if ( file_exists($dir) && ($dh = opendir($dir)) ) {
    while (($file = readdir($dh)) !== false) {
        if (is_file($dir.'/'.$file)) {
            unlink($dir.'/'.$file);
        }
    }
    closedir($dh);
}



// update ������
//$ver = '';
//if (preg_match('/^ref: (.+)$/', file_get_contents($projectDir.'/.git/HEAD'), $m)) {
//    if (file_exists($projectDir.'/.git/'.$m[1])) {
//        $ver = file_get_contents($projectDir.'/.git/'.$m[1]);
//    }
//}
//if (!$ver) {
//    $ver = file_get_contents($projectDir.'/.git/ORIG_HEAD');
//}
//file_put_contents(
//    $projectDir.'/site_id/config/version.php', 
//    '<?
//$config = array(
//    \'version\'   => \''.trim($ver).'\',
//);'
//);
