<?php
require_once ('Connection.php');

$select = "SELECT serviceID,serviceName FROM services WHERE displayStatus = 'show'";

$result = mysqli_query($link,$select);

if($result){
    while($service = mysqli_fetch_assoc($result)){
        $response[] = array("serviceID"=>$service['serviceID'],"serviceName"=>$service['serviceName']);
    }

}
echo json_encode($response);
