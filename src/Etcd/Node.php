<?php

namespace Etcd;

/**
 * A very simple Etcd Node
 *
 * @author Tom Morton
 */
class Node {

	private $node;
	private $api;

	public function __construct($node, $api) {
		$this->node = $node;
		$this->api = $api;

		if($this->isDir() && isset($this->node['nodes'])) {
			$nodes = array();
			foreach($this->node['nodes'] as $child) {
				$nodes[] = new Self($child, $api);
			}
			$this->node['nodes'] = $nodes;
		}
	}

	public function __get($key) 
	{
		if(isset($this->node[$key])) {
			return $this->node[$key];
		}
	}

	public function getExpiry()
	{
		if(isset($this->node['expiration'])) {
			return new \DateTime($this->node['expiration']);
		}

		return null;
	}

	public function expires()
	{
		return isset($this->node['expiration']);
	}

	public function isDir()
	{
		return isset($this->node['dir']);
	}

	public function delete($recursive=false) 
	{
		if($this->isDir()) {
			$this->api->delete($this->node['key'],true,$recursive);
		} else {
			$this->api->delete($this->node['key']);
		}
	}

	public function save() 
	{
		if(!$this->isDir()) {
			$this->api->set($this->node['key'],$this->node['value']);
		} else {
			// TODO: needs to be able to set TTL
		}
	}

	public function getChildren()
	{
		if(!$this->isDir()) {
			throw new \Etcd\Exception\KeyException($this->node['key'] . ' is not a directory');
		}

		if(!isset($this->node['nodes'])) {
			$refresh = $this->api->get($this->node['key']);
			$this->node['nodes'] = $refresh->nodes;
		}

		return $this->node['nodes'];
	}
}