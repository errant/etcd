<?php
namespace QED;

/**
 * Etcd Connection
 *
 * Wraps a GuzzleHTTP connection to the Etcd endpoint
 *
 * @author Tom Morton
 */
class Connection extends \Etcd\Connection {

    /**
     * Retrieve the API Class
     *
     * Install the QED Parser as we do so
     * 
     * @return object \Etcd\API\V2
     */
    public function getAPI()
    {
        $api = parent::getAPI();
        $api->setParser(new \QED\Parser);
    return $api;
    }
}
