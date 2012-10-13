<?php
class Relationship {

	private $id, $type, $node_start, $node_end;

	public function __construct($id, $type, $node_start, $node_end) {
		$this->id = $id;
		$this->type = $type;
		$this->node_start = $node_start;
		$this->node_end = $node_end;
	}

	public function getId() {
		return $this->id;
	}

	public function getType() {
		return $this->type;
	}

	public function getStartNode() {
		return $this->node_start;
	}

	public function getEndNode() {
		return $this->node_end;
	}
}
?>