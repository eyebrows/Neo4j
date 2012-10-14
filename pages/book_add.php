<?php
if(!defined('SITE_URL'))
	exit;

if($_POST && count($_POST)) {
	if($_POST['title'] && $_POST['author']) {
		$graph = new GraphDatabase();
		$node = $graph->createNode(array(
			'type'=>'Book',
			'title'=>$_POST['title'],
			'author'=>$_POST['author'],
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
	<h1>Add New Book</h1>
	<form name="BookAdd" action="<?=SITE_URL?>?page=<?=$_GET['page']?>" method="POST">
		<table class="node_form">
		<tr>
			<td>Title</td>
			<td><input type="text" name="title" value="<?=$_POST['title']?>"></td>
		</tr>
		<tr>
			<td>Author</td>
			<td><input type="text" name="author" value="<?=$_POST['author']?>"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="action" value="Add Book"></td>
		</tr>
		</table>
	</form>
</div>
