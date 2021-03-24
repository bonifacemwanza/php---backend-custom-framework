<?php
require_once('./assets/init.php');

$data         = array();
$first        = '';
$second       = '';
$api_requests = array('go_pro','wallet','ads');
$type         = (!empty($_GET['type'])) ? Secure($_GET['type']) : '';

if (!empty($_GET['first'])) {
    $first = Secure($_GET['first'], 0);
}
if (!empty($_GET['second'])) {
    $second = Secure($_GET['second'], 0);
}

if ($type  != 'ap' && !in_array($type,$api_requests)) {
    $hash_id = '';
    $is_error = 0;

    if (!empty($_POST['hash'])) {
        $hash_id = Secure($_POST['hash']);;
    }

    else if (!empty($_GET['hash'])) {
        $hash_id = Secure($_GET['hash']);
    }

    if (empty($hash_id)) {
        $is_error = 1;
    }

    else {
        if (CheckMainSession($hash_id) == false) {
            $is_error = 1;
        }
    }
    if ($is_error == 1) {
        header('Content-Type: application/json');
        $data = array('status' => 400, 'message' => 'bad-request');
        echo json_encode($data);
        exit();
    }
}

if (!empty($_GET['type'])) {
    $file = Secure($_GET['type']);
    if (file_exists("./ajax/$file.php")) {
        require "./ajax/$file.php";
    } else {
        $data = array('error' => 404, 'error_message' => 'type not found');
    }
}

header('Content-Type: application/json');
echo json_encode($data);
exit();
?>