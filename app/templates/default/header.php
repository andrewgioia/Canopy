<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $data[ 'title' ].' - '.SITETITLE; ?></title>
    <script src="<?php echo url::get_asset_path( 'js/jquery.min.js' ); ?>"></script>
    <script src="<?php echo url::get_asset_path( 'js/highcharts.js' ); ?>"></script>
    <script src="<?php echo url::get_asset_path( 'js/moment.min.js' ); ?>"></script>
    <link href="http://fonts.googleapis.com/css?family=Molengo" rel="stylesheet" type="text/css" />
    <link href="<?php echo url::get_asset_path( 'css/font-awesome.css' ); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo url::get_asset_path( 'css/style.css' ); ?>" rel="stylesheet" type="text/css">
</head>

<body>

<div id="wrapper">

    <header>
        <div class="colors"></div>
        <div class="logo">Canopy</div>
        <menu>
            <a href="/"><i class="fa fa-tachometer pull-up"></i>Dashboard</a>
            <a href="/"><i class="fa fa-calendar pull-up"></i>Calendar</a>
            <a href="/"><i class="fa fa-gear"></i>Settings</a>
        </menu>
    </header>

    <main>
