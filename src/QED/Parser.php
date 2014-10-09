<?php
namespace QED;

class Parser implements \Etcd\Interfaces\Parser {

	private $api;

	public function setAPI(\Etcd\Interfaces\API $api)
	{
		$this->api = $api;
	}

	public function parse($response)
	{
		if($response['errorCode']) {

		} else {
			if(isset($response['node']['dir'])) {
				return new \QED\Nodes\Dir($response['node'], $this->api);
			} else {
				return new \QED\Nodes\Key($response['node'], $this->api);
			}


		}
	}

}
