<?php
class GraphDatabase {

	private $uri, $http, $factory;

	public function __construct($uri=null) {
		$this->uri = $uri?$uri:CONFIG_NEO4J_URI;
		$this->http = new HTTPWrapper();
		$this->factory = new NodeFactory();
	}

//accepts array of properties to live in a new node, returns the created Node on success
	public function createNode($properties) {
		list($response, $http_code) = $this->http->post($this->uri.'db/data/node', $properties);
		switch($http_code) {
			case 201:
				$node = $this->factory->createNodeFromJSON($response);
				break;
			default:
				throw new Exception('Unknown HTTP Code ('.$http_code.')');
		}
		return $node;
	}

//pulls a node from the db when requested by id, passes the JSON to the factory to create the object
	public function fetchNode($id) {
		list($response, $http_code) = $this->http->get($this->uri.'db/data/node/'.$id);
		switch($http_code) {
			case 200:
				$node = $this->factory->createNodeFromJSON($response);
				$this->fetchRelationships($node, 'out');
				$this->fetchRelationships($node, 'in');
				break;
			case 404:
				throw new Exception('Node Not Found ('.$id.')');
			default:
				throw new Exception('Unknown HTTP Code ('.$http_code.')');
		}
		return $node;
	}

//pulls relationships for a Node, passes the JSON to factory to inject them in to the Node object
	private function fetchRelationships($node, $class) {
		if($class!='out' && $class!='in')
			throw new Exception('Invalid Relationships Class Request ('.$class.')');
		list($response, $http_code) = $this->http->get($this->uri.'db/data/node/'.$node->getId().'/relationships/'.$class);
		switch($http_code) {
			case 200:
				$this->factory->injectRelationships($node, $class, $response, $this);
				break;
			case 404:
				throw new Exception('Relationships '.$class.' Not Found ('.$node->getId().')');
			default:
				throw new Exception('Unknown HTTP Code ('.$http_code.')');
		}
	}

//used for populating an existing "lazy loading" Node (just containing its ID) with its properties
	public function fetchNodeData($node) {
		list($response, $http_code) = $this->http->get($this->uri.'db/data/node/'.$node->getId());
		switch($http_code) {
			case 200:
				$this->factory->injectProperties($node, $response);
				$this->fetchRelationships($node, 'out');
				$this->fetchRelationships($node, 'in');
				break;
			case 404:
				throw new Exception('Node Not Found ('.$id.')');
			default:
				throw new Exception('Unknown HTTP Code ('.$http_code.')');
		}
		return $node;
	}
}
?>