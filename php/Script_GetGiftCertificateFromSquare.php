<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/20/2016
 * Time: 1:50 PM
 */
$base = dirname(dirname(__FILE__));
require_once ($base . '/php/Class.GiftCertificate.php');

    $today = date('Y-m-d');

    $date = new DateTime($today);
    $date->modify('+1 day');
    $tomorrow= $date->format('Y-m-d');

    $certificate = new GiftCertificate();
    $certificate->CreateCertificate($today,$tomorrow);

