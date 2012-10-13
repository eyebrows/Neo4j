<?php
function __autoload($name) {
	require_once('classes/'.$name.'.php');
}
?>