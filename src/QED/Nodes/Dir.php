<?php
namespace QED\Nodes;

/**
 * Key Node
 *
 * @author Tom Morton
 */
class Dir extends \QED\Node implements \SeekableIterator, \ArrayAccess {

    protected $position = 0;

    public function __construct($data, \Etcd\Interfaces\API $api) 
    {
        parent::__construct($data, $api);

        $nodes = array();

        if(isset($this->data['nodes']) && is_array($this->data['nodes'])) {
            foreach($this->data['nodes'] as $node) {
                if(isset($node['dir'])) {
                    $nodes[$this->localKey($node['key'])] = new \QED\Nodes\Dir($node, $api);
                } else {
                    $nodes[$this->localKey($node['key'])] = new \QED\Nodes\Key($node, $api);
                }
            }
        }

        $this->data['nodes'] = $nodes;
    }

    private function localKey($nodeKey) 
    {
        return str_replace(rtrim($this->key) . '/', '', $nodeKey);
    }

    private function castArray()
    {
        return array_values($this->data['nodes']);
    }

    /** Iterator */
    public function rewind() 
    {
        $this->position = 0;
    }

    public function current() 
    {
        return $this->castArray()[$this->position];
    }

    public function key() 
    {
        return $this->position;
    }

    public function next() 
    {
        ++$this->position;
    }

    public function valid() 
    {
        return isset($this->castArray()[$this->position]);
    }

    public function seek($position) 
    {
        return $this->offsetGet($position);
    }

    /** Array Access */
    public function offsetExists ( $offset )
    {
        return isset($this->data['nodes'][$offset]);
    }

    public function offsetGet ( $offset ) 
    {
        return $this->data['nodes'][$offset];
    }

    public function offsetSet ( $offset, $value )
    {
        if($offset === Null) {
            $this->data['nodes'][] = $this->api->create($this->key, $value);
        } elseif(is_string($offset) || is_integer($offset)) {
            $offset = rtrim($this->key,'/') . '/' . $offset;
            foreach($this->data['nodes'] as $node) {
                if($node->key == $offset) {
                    $node->value = $value;
                    $node->save();
                }
            }
            $this->data['nodes'][] = $this->api->set($offset, $value);
        } else {
            throw new \Exception('Invalid offset');
        }

    }

    public function offsetUnset ( $offset )
    {
        $node = $this->offsetGet($offset); 
        $this->api->delete($node->key);
        unset($this->nodes[$offset]);
    }
    public function save()
    {
        if($this->createdIndex) {

        } else {
            // Create Directory
            $this->api->set($this->key, Null, array('dir' => 'true'));
        }
        
    }

}