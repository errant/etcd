<?php
namespace QED;

abstract class Node {

    protected $api;
    protected $data;

    public function __construct($data, \Etcd\Interfaces\API $api) 
    {
        $this->data = $data;
        $this->api = $api;
    }

    public function __get($key) 
    {
        if(isset($this->data[$key])) {
            return $this->data[$key];
        }
    }


  public function __set($key, $value) 
  {
    if(isset($this->data[$key])) {
      $this->data[$key] = $value;
    }
  }

  abstract public function save();


}