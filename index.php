<?php
    require "config/session.php";
    
    $pages = [
        "home" => "home.php",
        "product" => "product.php",
        "gallery" => "gallery.php"
    ];

    if(isset($_GET['action'])){
        if(array_key_exists($_GET['action'],$pages)){
            $page = $pages[$_GET['action']];
                $title = $_GET['action'];
        }else{
            $page = "404.php";
            $title = "404 NOT FOUND";
        }
    }else{
        $page = $pages['home'];
        $title = "Stock";
    }

    include("partials/head.php");
    include("partials/nav.php");

    include('pages/'.$page);

    include("partials/foot.php");

