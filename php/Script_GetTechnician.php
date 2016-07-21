<?php
    require_once ('Connection.php');

    $select = "SELECT technicianID,firstName,lastName FROM technicians ".
        "INNER JOIN usertype ON technicians.userTypeID = usertype.userTypeID ".
        "WHERE usertype.userTypeID = 3 ORDER BY firstName ASC";

    $result = mysqli_query($link,$select);

    if($result){
        while($tech =  mysqli_fetch_assoc($result)){
            $response[] = array("techID"=>$tech['technicianID'],"name"=>$tech['firstName']. " ". $tech['lastName']);
        }
    }
    echo json_encode($response);