<?php

/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/15/2016
 * Time: 12:53 PM
 */
class Car
{
    private $model;


    public function SetModel($model){
        $this->model = $model;
    }

    public function GetModel(){
        return $this->model;
    }

}