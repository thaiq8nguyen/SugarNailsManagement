<?php
require_once ('Connection.php');

if(!empty($_POST['techid']) && !empty($_POST['date'])){

    $id = $_POST['techid'];
    $date = $_POST['date'];

    $query = "SELECT status FROM entries WHERE techid = ".$id." AND date = '".$date."'";
    $result = mysqli_query($link,$query);

    if($result){
        $num = mysqli_num_rows($result);
        if($num == 1){
            $row = mysqli_fetch_assoc($result);
            $status = $row['status'];
        }
        else if($num == 0){
            $status = "no entry";
        }
    }
    echo $status;
}