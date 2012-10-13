<?php
class Node {

	private $id, $properties, $relationships;
	private $loaded = false, $graph_db = null;

	public function __construct($id, $properties, $graph_db=null) {
		$this->id = $id;
		if(is_array($properties))
			$this->setProperties($properties);
		else
			$this->graph_db = $graph_db;
		$this->relationships = array(
			'out'=>array(),
			'in'=>array(),
		);
	}

	public function getId() {
		return $this->id;
	}

	public function setProperties($properties) {
		$this->properties = $properties;
		$this->loaded = true;
	}

	public function getProperties() {
		$this->checkPropertiesLoaded();
		return $this->properties;
	}

	public function getProperty($name) {
		$this->checkPropertiesLoaded();
		return $this->properties[$name];
	}

	public function injectRelationship($rel, $class) {
		$this->relationships[$class][$rel->getId()] = $rel;
	}

	public function getRelationshipsOut() {
		return $this->relationships['out'];
	}

	public function getRelationshipsIn() {
		return $this->relationships['in'];
	}

	private function checkPropertiesLoaded() {
		if(!$this->loaded) {
			if(!$this->graph_db)
				throw new Exception('Lazy Loader Missing graph_db');
			$this->graph_db->fetchNodeData($this);
		}
	}
}
?>