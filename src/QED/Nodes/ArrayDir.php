<?php
namespace QED\Nodes;

/**
 * Array Node
 *
 * @author Tom Morton
 */
class ArrayDir extends Dir implements \ArrayAccess {

	public function offsetExists ( $offset )
	{
		return isset($this->nodes[$offset]);
	}

	public function offsetGet ( $offset ) 
	{
		return $this->nodes[$offset];
	}

	public function offsetSet ( $offset, $value )
	{
		$this->api->create($this->key, $value);
	}

	public function offsetUnset ( $offset )
	{
		$node = $this->offsetGet($offset); 
		$this->api->delete($node->key);
		unset($this->nodes[$offset]);
	}

}