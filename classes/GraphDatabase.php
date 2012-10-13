<?php
class GraphDatabase {

	private $uri, $http, $factory;

	public function __construct($uri) {
		$this->uri = $uri;
		$this->http = new HTTPWrapper();
		$this->factory = new NodeFactory();
	}

	public function fetchNode($id) {
		list($response, $http_code) = $this->http->request($this->uri.'db/data/node/'.$id);
		switch($http_code) {
			case 200:
				$node = $this->factory->createNodeFromDB($id, $response);
				$this->fetchRelationships($node, 'out');
				$this->fetchRelationships($node, 'in');
				return $node;
			case 404:
				throw new Exception('Node Not Found ('.$id.')');
			default:
				throw new Exception('Unknown HTTP Code ('.$http_code.')');
		}
	}

	private function fetchRelationships($node, $class) {
		if($class!='out' && $class!='in')
			throw new Exception('Invalid Relationships Class Request ('.$class.')');
		list($response, $http_code) = $this->http->request($this->uri.'db/data/node/'.$node->getId().'/relationships/'.$class);
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

	public function fetchNodeData($node) {
		list($response, $http_code) = $this->http->request($this->uri.'db/data/node/'.$node->getId());
		switch($http_code) {
			case 200:
				$this->factory->injectProperties($node, $response);
				$this->fetchRelationships($node, 'out');
				$this->fetchRelationships($node, 'in');
				return $node;
			case 404:
				throw new Exception('Node Not Found ('.$id.')');
			default:
				throw new Exception('Unknown HTTP Code ('.$http_code.')');
		}
	}
}
?>