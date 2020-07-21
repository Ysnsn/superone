<!DOCTYPE html>
<html id="bg">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
	<title>OneIndex <?php e($title);?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@0.4.3/dist/css/mdui.min.css">
	
	<style>
        .mc-drawer {
            background-color: rgba(255, 255, 255, 0.5);
<?php if( oneindex::is_mobile()): ?>
 background-color: rgba(255, 255, 255, 0.8);
<?php endif ?>
        }
 <?php if( !oneindex::is_mobile()): ?>
        .mdui-toolbar {

            background-image: url(<?php echo config("bgimg")?>) !important;
            color: #FFF;
background-position: center  0px;
background-size: cover;
        }
#menu {

            background-image: url(<?php echo config("bgimg")?>) !important;
            color: #FFF;
background-position: center  0px;
background-size: cover;
        }
<?php endif?>

 <?php if( oneindex::is_mobile()): ?>
        .mdui-toolbar {

            background-image: url(<?php echo config("mobileimg")?>) !important;
            color: #FFF;

        }

<?php endif?>
        .mdui-toolbar>* {
            padding: 0 6px;
            margin: 0 2px;
            opacity: 0.5;
        }

        .mdui-toolbar>.mdui-typo-headline {
            padding: 0 1px 0 0;
        }

        .mdui-toolbar>i {
            padding: 0;
        }

        .mdui-toolbar>a:hover,
        a.mdui-typo-headline,
        a.active {
            opacity: 1;
        }

        .mdui-container {
            max-width: 1024px;
        }

        .mdui-list-item {
            -webkit-transition: none;
            transition: none;
        }

        .mdui-list>.th {
            background-color: initial;
        }

        .mdui-list-item>a {
            width: 100%;
            line-height: 45px
        }

        .mdui-row>.mdui-list>.mdui-list-item {
            margin: 0px 0px 0px 0px;
            padding: 0;
           
        }
	
        .mdui-toolbar>a:last-child {
            opacity: 1;
        }

        #instantclick-bar {
            background: white;

        }

        .mdui-video-fluid {
            height: -webkit-fill-available;
        }

        .dplayer-video-wrap .dplayer-video {
            height: -webkit-fill-available !important;
        }

        .gslide iframe,
        .gslide video {
            height: -webkit-fill-available;
        }

        @media screen and (max-width:800px) {
            .mdui-list-item .mdui-text-right {
               display: none;
            }

            .mdui-container {
                width: 100% !important;
                margin: 0px;
            }

            .1111mdui-toolbar>* {
                display: none;
            }

            .mdui-toolbar>a:last-child,
            .mdui-toolbar>a:nth-last-of-type(2),
            .mdui-toolbar>.mdui-typo-headline,
            .mdui-toolbar>i:first-child,
            .mdui-toolbar-spacer {
                display: block;
            }
        }

        .spec-col {
            padding: .9em;
            display: flex;
            align-items: center;
            white-space: nowrap;
            flex: 1 50%;
            min-width: 225px
        }

        .spec-type {
            font-size: 1.35em
        }

        .spec-value {
            font-size: 1.25em
        }

        .spec-text {
            float: left
        }

        .device-section {
            padding-top: 30px
        }

        .spec-device-img {
            height: auto;
            height: 340px;
            padding-bottom: 30px
        }

        #dl-header {
            margin: 0
        }

        #dl-section {
            padding-top: 10px
        }

        #dl-latest {
            position: relative;
            top: 50%;
            transform: translateY(-50%)
        }

        .mdui-typo.mdui-shadow-3 {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .nexmoe-item {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .mdui-row {
            margin-right: 1px;
            margin-left: 1px;
        }

       html, body {
            width: -webkit-fill-available;
            height: -webkit-fill-available;
            background-size: cover;
            <?php if( !oneindex::is_mobile()): ?> background-image: url(<?php echo config("bgimg")?>) !important;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            <?php else: ?> background-image: url(<?php echo config("mobileimg")?>) !important;
            background-position: center ;
            background-attachment: fixed;
            background-repeat: no-repeat;
            <?php endif?>
        }
        
         .thumb .th {
            display: none;
        }

        .thumb .mdui-text-right {
            display: none;
        }

        .thumb .mdui-list-item a,
        .thumb .mdui-list-item {
            width: 213px;
            height: 230px;
            float: left;
            margin: 10px 10px !important;
        }

        .thumb .mdui-col-xs-12,
        .thumb .mdui-col-sm-7 {
            width: 100% !important;
            height: 230px;
        }

        .thumb .mdui-list-item .mdui-icon {
            font-size: 100px;
            display: block;
            margin-top: 40px;
            color: #7ab5ef;
        }

        .thumb .mdui-list-item span {
            float: left;
            display: block;
            text-align: center;
            width: 100%;
            position: absolute;
            top: 180px;
        }

        .thumb .forcedownload {
            display: none;
        }

        .mdui-checkbox-icon::after {
            border-color: transparent;
        }
        .mdui-fab-fixed, .mdui-fab-wrapper {
   
    bottom: 64px;
}
        
        
    </style>
</head>
<body >
	<header class="mdui-appbar mdui-color-theme-accent">
		<div class="mdui-toolbar mdui-container">
			<a href="/" class="mdui-typo-headline">OneIndex</a>
			<?php foreach((array)$navs as $n=>$l):?>
			<i class="mdui-icon material-icons mdui-icon-dark" style="margin:0;">chevron_right</i>
			<a href="<?php e($l);?>"><?php e($n);?></a>
			<?php endforeach;?>
			<!--<a href="javascript:;" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">refresh</i></a>-->
		</div>
	</header>
	
	<div class="mdui-container">
    	<?php view::section('content');?>
  	</div>
</body>
</html>