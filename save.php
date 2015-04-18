<?php
//save.php
/*****************************************************
Saves the base 64 encoded strings for the thumbnail and full sized image

Requires 
$_POST['dev'] - device unique id
$_POST['img'] - base64 encoded string representing the full size image
$_POST['thumb'] - base64 encoded string representing the thumbnail version of the image

Returns JSON
code should be zero if there is no error
The id parameter will be the unique image id from the database
{"code":0, "message": "Feedback message", "id":123}

if code is something else then there is an error and no id for the image
{"code":423, "message":"error message for you" }
*****************************************************/

require_once("db.inc.php");
header("Content-Type: application/json");

if( isset( $_POST['dev'] ) && isset($_POST['img']) && isset($_POST['thumb']) ){
    //we have the device id
    //save the thumbnail and image in the database
    $dev_id = trim($_POST['dev']);
    $full_img = trim($_POST['img']);
    $thumb = trim($_POST['thumb']);
	//do preg_replace to change the spaces back into plus signs
	$full_img = preg_replace('/\s/', '+', $full_img);
	$thumb = preg_replace('/\s/',  '+', $thumb);
    
    $sql = "INSERT INTO w15_final(device_id, thumbnail, full_img) VALUES(?, ?, ?)";
    $rs = $pdo->prepare($sql);
    $ret = $rs->execute( array($dev_id, $thumb, $full_img) );
    if($ret){
        //return the success message and the image id
        echo '{"code":0, "message":"Success", "id":';
        echo $pdo->lastInsertId();
        echo '}';
    }else{
        //failed to run query.... error
        $errorArray = $rs->errorInfo( );
        echo '{"code":543, "message":"Unable to save the image in the database at this time. SQL Error Code: ' . $errorArray[0] . '"}';
    }
}else{
    //no device id provided
    echo '{"code":423, "message":"Missing required parameter(s)"}';
}
exit();
$pdo = null;
?>