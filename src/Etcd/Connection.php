<?php
namespace Etcd;
/**
 * Etcd Connection
 *
 * Wraps a GuzzleHTTP connection to the Etcd endpoint
 *
 * @author Tom Morton
 */
class Connection extends \GuzzleHttp\Client {

	/**
	 * @var $server Server Hostmame and Port
	 */
	private $server;
	/**
	 * @var $version Cached Etcd version
	 */
	private $version;
	/**
	 * @var $APIVersion API Version Number
	 */
	private $APIVersion;

	/**
	 * Split the Etcd version number from the version string
	 * 
	 * @param string $versionString e.g. etcd 0.4.6
	 * @return string
	 */
	private function _extractVersionNumber($versionString)
	{
		$versionArray = explode(' ',$versionString);
		return $versionArray[1];
	}

	/**
	 * Create the Connection Object
	 * 
	 * @param string $server Hostname and Port
	 * @param integer $APIVersion Version of the etcd API to use
	 */
	public function __construct($server = 'http://localhost:4001', $APIVersion = 2) {
		$this->server = $server;
		$this->setAPIVersion($APIVersion);

		parent::__construct(array('base_url' => $server));
	}

	/**
	 * Get the Server
	 *
	 * @return string
	 */
	public function getServer()
	{
		return $this->server;
	}

	/**
	 * Get the Etcd Version
	 *
	 * @return string
	 */
	public function getVersion()
	{
		if(!$this->version) {
			$response = $this->get('/version');
			$this->version = $this->_extractVersionNumber((string) $response->getBody());
		}
		return $this->version;
	}

	/**
	 * Set the API Version
	 *
	 * @param integer $version
	 * @throws \Etcd\Exception\APIException
	 */
	public function setAPIVersion($version)
	{
		if(!in_array($version,array(2))) {
			throw new \Etcd\Exception\APIException('Unsupported API version: ' . $version);
		}

		$this->APIVersion = $version;
	}

	/**
	 * Get the API Version
	 *
	 * @return integer
	 */
	public function getAPIVersion()
	{
		return $this->APIVersion;
	}

	/**
	 * Get API Interface
	 *
	 * @return object \Etcd\Interfaces\Object
	 */
	public function getAPI()
	{
		$apiClassName = '\Etcd\Api\V' . $this->APIVersion;
		return new $apiClassName($this);
	}

	/**
	 * Get a Key Endpoint
	 *
	 * @param string $key
	 * @return string
	 */
	public function getKeyEndpoint($key)
	{
		return 'v' . $this->APIVersion . '/keys/' . $key;
	}
}
