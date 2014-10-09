<?php
namespace Etcd\API\V2;

class Parser implements \Etcd\Interfaces\Parser {

	private $api;

	public function setAPI($api)
	{
		$this->api = $api;
	}

	public function parse($response)
	{
		if(isset($response['errorCode'])) {
			throw new \Etcd\Exception\APIException('(' . $response['errorCode'] . ') ' . $response['message'] . ': ' . $response['cause']);
		}

		return new \Etcd\Node($response['node'], $this->api);
	}

}
