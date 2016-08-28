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
    if(isset($_POST['id']))
    {
        $dbf = DBFunctions::getInstance();

        $id = $_POST['id'];

        echo json_encode($dbf->deleteRoom($id));
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