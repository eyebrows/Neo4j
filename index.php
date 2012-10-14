<?php
include('config.php');

$nav = array(
	'browse'=>'Browse Graph',
	'person_add'=>'Add Person',
	'book_add'=>'Add Book',
	'rel_add'=>'Add Relationship',
);

if(!isset($_GET['page']) || !in_array($_GET['page'], array_keys($nav)))
	$_GET['page'] = key($nav);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Neo4j Experiment</title>
<link rel="stylesheet" media="all" href="<?=SITE_URL?>css/html5reset-1.6.1.css" />
<link rel="stylesheet" media="all" href="<?=SITE_URL?>css/common.css" />
<link rel="stylesheet" media="all" href="<?=SITE_URL?>css/site.css" />
<!--[if lt IE 9]>
<script src="<?=SITE_URL?>js/html5.js"></script>
<![endif]-->
<script src="<?=SITE_URL?>js/jquery/jquery-1.7.2.min.js"></script>
<script src="<?=SITE_URL?>js/jquery/jquery-ui-1.8.20.custom.min.js"></script>
<script src="<?=SITE_URL?>js/site.js"></script>
</head>
<body>
<ul id="nav">
<?php
foreach($nav as $k=>$v)
	print '<li'.($k==$_GET['page']?' class="current_link"':'').'><a href="'.SITE_URL.'?page='.$k.'">'.$v.'</a></li>';
?>
</ul>
<?php
require_once('pages/'.$_GET['page'].'.php');
?>
</body>
</html>