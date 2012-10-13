<?php
include('autoload.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Neo4j Experiment</title>
<link rel="stylesheet" media="all" href="css/html5reset-1.6.1.css" />
<link rel="stylesheet" media="all" href="css/common.css" />
<link rel="stylesheet" media="all" href="css/site.css" />
<!--[if lt IE 9]>
<script src="js/html5.js"></script>
<![endif]-->
<script src="js/jquery/jquery-1.7.2.min.js"></script>
<script src="js/jquery/jquery-ui-1.8.20.custom.min.js"></script>
<script src="js/site.js"></script>
</head>
<body>
<?php
if(!$_GET['node']) {
	$_GET['node'] = 1;
?>
<p>
	Not sure how to pull an list of "all nodes where type=Person" (via an index) yet, and there's no ?node= in the URL, so let's pull node #1
</p>
<?php
}
$graph = new GraphDatabase('http://localhost:7474/');
$node = $graph->fetchNode($_GET['node']);
?>
<div class="boxout_node">
	<h1>Node ID: <?=$node->getId()?></h1>
	<h2>Properties</h2>
	<ul>
<?php
foreach($node->getProperties() as $key=>$value)
	print '<li><i>'.$key.'</i> = '.$value.'</li>';
?>
	</ul>
<?php
if(count($rels = $node->getRelationshipsOut())) {
?>
	<h2>Outgoing Relationships</h2>
	<ul>
<?php
	foreach($rels as $relationship) {
		$rel_node = $relationship->getEndNode();
		if($rel_node->getProperty('type')=='Person')
			$descriptor = $rel_node->getProperty('name');
		else if($rel_node->getProperty('type')=='Book')
			$descriptor = $rel_node->getProperty('title');
		print '<li>'.$relationship->getType().' <a href="/?node='.$rel_node->getId().'">'.$descriptor.'</a></li>';
	}
?>
	</ul>
<?php
}
if(count($rels = $node->getRelationshipsIn())) {
?>
	<h2>Incoming Relationships</h2>
	<ul>
<?php
	foreach($rels as $relationship) {
		$rel_node = $relationship->getStartNode();
		if($rel_node->getProperty('type')=='Person')
			$descriptor = $rel_node->getProperty('name');
		else if($rel_node->getProperty('type')=='Book')
			$descriptor = $rel_node->getProperty('title');
		print '<li><a href="/?node='.$rel_node->getId().'">'.$descriptor.'</a> '.$relationship->getType().'</li>';
	}
?>
	</ul>
<?php
}
?>
</div>

</body>
</html>