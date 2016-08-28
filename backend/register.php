<?php
/**
 * Created by PhpStorm.
 * User: lazar
 * Date: 8/27/16
 * Time: 7:11 PM
 */

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST');

require_once "DBFunctions.php";

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"]) && isset($_POST["firstname"]) && isset($_POST["lastname"])) {
    $db = DBFunctions::getInstance();

    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];

    if($db->checkIfUsernameExists($username) == true) {
        $ret = array();
        $ret['status'] = 'ERROR';
        $ret['error'] = 'Korisnicko ime vec postoji.';
        echo json_encode($ret);
    } else
        echo json_encode($db->register($firstName, $lastName, $username, $password, $email));
} else {
    $ret = array();
    $ret['status'] = 'ERROR';
    $ret['error'] = 'Niste uneli sva polja.';
    echo json_encode($ret);
}

?>