<?php
if(!defined('SITE_URL'))
	exit;

if(!$_GET['node']) {
	$_GET['node'] = 1;
?>
<p>
	Not sure how to pull an list of "all nodes where type=Person" (via an index?) yet, and there's no ?node= in the URL, so let's pull node #1
</p>
<?php
}
$graph = new GraphDatabase();
$node = $graph->fetchNode($_GET['node']);
?>
<div class="boxout">
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
		print '<li>'.$relationship->getType().' <a href="'.SITE_URL.'?page='.$_GET['page'].'&node='.$rel_node->getId().'">'.$descriptor.'</a></li>';
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
		print '<li><a href="'.SITE_URL.'?page='.$_GET['page'].'&node='.$rel_node->getId().'">'.$descriptor.'</a> '.$relationship->getType().'</li>';
	}
?>
	</ul>
<?php
}
?>
</div>
