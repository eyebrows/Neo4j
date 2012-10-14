<?php
class NodeFactory {

	public function __construct() {
	}

	public function createNodeFromJSON($json) {
		$assoc = json_decode($json, 'return_assoc');
		return new Node(end(explode('/', $assoc['self'])), $assoc['data']);
	}

	public function injectProperties($node, $json) {
		$assoc = json_decode($json, 'return_assoc');
		$node->setProperties($assoc['data']);
	}

	public function injectRelationships($node, $class, $json, $graph_db) {
		$assoc = json_decode($json, 'return_assoc');
		foreach($assoc as $rel) {
			$self_id = end(explode('/', $rel['self']));
			if($class=='out') {
				$end_id = end(explode('/', $rel['end']));
				$end_node = new Node($end_id, null, $graph_db);
				$relationship = new Relationship($self_id, $rel['type'], $node, $end_node);
			}
			else {
				$start_id = end(explode('/', $rel['start']));
				$start_node = new Node($start_id, null, $graph_db);
				$relationship = new Relationship($self_id, $rel['type'], $start_node, $node);
			}
			$node->injectRelationship($relationship, $class);
		}
	}
}
?>