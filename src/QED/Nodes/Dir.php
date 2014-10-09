<?php
namespace QED\Nodes;

/**
 * Key Node
 *
 * @author Tom Morton
 */
class Dir extends \QED\Node {


	public function __construct($data, \Etcd\Interfaces\API $api) 
	{
		parent::__construct($data, $api);

		$nodes = array();

		foreach($this->data['nodes'] as $node) {
			if(isset($node['dir'])) {
				$nodes[] = \QED\Nodes\Dir($node, $api);
			} else {
				$nodes[] = \QED\Nodes\Key($node, $api);
			}
		}
	}

}