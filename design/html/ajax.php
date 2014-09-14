<?php
header('Content-Type: application/json');
//$data = json_decode(file_get_contents('php://input'));
//$id = $data->{'id'};
$fetchedModels = array('location' => 'left', 'autocheck' => 'true');
echo json_encode($fetchedModels);