<?php
namespace QED;

class Node {

	private $api;
	private $data;

	public function __construct($data, \Etcd\Interfaces\API $api) 
	{
		$this->data = $data;
		$this->api = $api;
	}

	public function __get($key) 
	{
		if(isset($this->node[$key])) {
			return $this->node[$key];
		}
	}


}