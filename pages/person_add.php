<?php
if(!defined('SITE_URL'))
	exit;

if($_POST && count($_POST)) {
	if($_POST['name']) {
		$graph = new GraphDatabase();
		$node = $graph->createNode(array(
			'type'=>'Person',
			'name'=>$_POST['name'],
		));

		$okay = 'Node created | <a href="'.SITE_URL.'?page=browse&node='.$node->getId().'">View</a>';
	}
	else
		$error = 'Please complete all fields';
}

if($okay)
	print '<div class="message okay">'.$okay.'</div>';
if($error)
	print '<div class="message error">'.$error.'</div>';
?>
<div class="boxout">
	<form name="PersonAdd" action="<?=SITE_URL?>?page=<?=$_GET['page']?>" method="POST">
		<table>
		<tr>
			<td>Name</td>
			<td><input type="text" name="name"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="action" value="Add"></td>
		</tr>
		</table>
	</form>
</div>
