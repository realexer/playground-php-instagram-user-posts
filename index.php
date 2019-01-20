<?php

set_time_limit(0);
date_default_timezone_set('UTC');
require __DIR__.'/vendor/autoload.php';

$view = new \app\View\InstagramPosts();
$view->load();

//$dataProvider = new InstagramDataProvider();
//$dataProvider->connect(new MGP25Client());

//var_dump($dataProvider->getRecentPosts("https://www.instagram.com/goalatasaray/"));
//var_dump($dataProvider->getRecentPosts("goalatasaray"));