<?php
/**
 * Created by PhpStorm.
 * User: lazar
 * Date: 8/28/16
 * Time: 7:01 AM
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, Token, token, TOKEN');

require_once "DBFunctions.php";

if(isset($_SERVER['HTTP_TOKEN']))
    if(isset($_POST['roomname']) && isset($_POST['beds']) && isset($_POST['size']) && isset($_POST['tv']))
    {
        $dbf = DBFunctions::getInstance();

        $roomname = $_POST['roomname'];
        $beds = $_POST['beds'];
        $size = $_POST['size'];
        $tv = $_POST['tv'];

        echo json_encode($dbf->newRoom($roomname, $beds, $size, $tv));
    }
    else
    {
        $ret = array();
        $ret['status'] = 'ERROR';
        $ret['error'] = 'Niste uneli sva polja.';
        echo json_encode($ret);
    }
else
{
    $ret = array();
    $ret['status'] = 'ERROR';
    $ret['error'] = 'Nemate ovlascenje za ovu akciju.';
    echo json_encode($ret);
}

?>