<?php
namespace QED\Nodes;

/**
 * Key Node
 *
 * @author Tom Morton
 */
class Key extends \QED\Node {


  public function save()
  {
      $this->api->set($this->key, $this->value);
  }

}