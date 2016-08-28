<?php
/**
 * Created by PhpStorm.
 * User: lazar
 * Date: 8/28/16
 * Time: 8:09 AM
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, Token, token, TOKEN');

require_once "DBFunctions.php";

$dbf = DBFunctions::getInstance();

echo json_encode($dbf->allRooms());
?>