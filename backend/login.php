<?php
/**
 * Created by PhpStorm.
 * User: lazar
 * Date: 8/28/16
 * Time: 6:01 AM
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once "DBFunctions.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $dbf = DBFunctions::getInstance();

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($dbf->checkLogin($username, $password) == true)
        echo json_encode($dbf->login($username));
    else {
        $ret = array();
        $ret['status'] = 'ERROR';
        $ret['error'] = 'Ne postoji korisnik sa takvim kredencijalima.';
        echo json_encode($ret);
    }
} else {
    $ret = array();
    $ret['status'] = 'ERROR';
    $ret['error'] = 'Niste uneli sva polja.';
    echo json_encode($ret);
}

?>