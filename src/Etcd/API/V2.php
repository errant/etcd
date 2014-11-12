<?php
namespace Etcd\API;

class V2 implements \Etcd\Interfaces\API {

    private $connection;

    private function getEndpoint($key)
    {
        return $this->connection->getKeyEndpoint($key);
    }

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->setParser(new \Etcd\API\V2\Parser);
    }

    public function setParser(\Etcd\Interfaces\Parser $parser)
    {
        $this->parser = $parser;
        $this->parser->setAPI($this);
    }

    public function create($key, $value, $options = array())
    {
        $uri = $this->getEndpoint($key);

        $body = array_merge(array('value' => $value), $options);

        $response = $this->connection->post($uri,array('body' => $body, 'exceptions' => false));

        return $this->parser->parse($response->json());
    }

    public function set($key, $value, $options = array())
    {
        $uri = $this->getEndpoint($key);

        $body = array_merge(array('value' => $value), $options);

        $response = $this->connection->put($uri,array('body' => $body, 'exceptions' => false));

        return $this->parser->parse($response->json());
    }
    
    public function get($key)
    {
        $uri = $this->getEndpoint($key);

        $response = $this->connection->get($uri,array('exceptions' => false));

        return $this->parser->parse($response->json());
    }

    public function delete($key, $dir = false, $recursive = false)
    {
        $uri = $this->getEndpoint($key);

        $query = array();
        if($dir) {
            $query['dir'] = 'true';
        }
        if($recursive) {
            $query['recursive'] = 'true';
        }

        $response = $this->connection->delete($uri,array('exceptions' => false, 'query' => $query));

        return $this->parser->parse($response->json());
    }
}