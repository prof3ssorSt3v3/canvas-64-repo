<?php
//get.php
/*****************************************************
Returns the base 64 encoded string representing the full sized image

Requires 
$_REQUEST['dev'] - device unique id
$_REQUEST['img'] - unique image id to match in the database

Returns JSON
code should be zero if there is no error
The id parameter will be the unique image id from the database
{"code":0, "message": "Feedback message", "id":123, "data":"base 64 encoded string representing the full size image"}

if code is something else then there is an error and no data for the image
{"code":423, "message":"error message for you" }
*****************************************************/

require_once("db.inc.php");
header("Content-Type: application/json");

if( isset( $_GET['dev'] ) && isset($_GET['img_id']) ){
    //we have the device id
    //Retrieve matching records for device
    $dev_id = trim($_GET['dev']);
    $img_id = intval($_GET['img_id']);
    
    $sql = "SELECT img_id, full_img FROM w15_final WHERE device_id=? AND img_id=?";
    $rs = $pdo->prepare($sql);
    $ret = $rs->execute( array($dev_id, $img_id) );
    if($ret){
        //need to check if row count > 0
        $count = $rs->rowCount();
        if($count > 0){
            $row = $rs->fetch();
            //only one row so no loop
            echo '{"code":0, "message":"Success", "id":';
            echo $row['img_id'] . ', "data":"';
            echo $row['full_img'] .'"';
            echo '}';
        }else{
            //no matches
            echo '{"code":333, "message":"No matches for this device and image reference"}';
        }
    }else{
        //failed to run query.... error
        $errorArray = $rs->errorInfo( );
        echo '{"code":543, "message":"Unable to fetch data from database at this time. SQL ErrorCode: ' . $errorArray[0] . '"}';
    }
}else{
    //no device id provided
    echo '{"code":423, "message":"Missing required parameter(s)."}';
}

exit();
$pdo = null;
?>