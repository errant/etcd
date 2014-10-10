<?php
namespace Etcd\Interfaces;

interface Parser {

    public function setAPI(\Etcd\Interfaces\API $api);
    public function parse($response);
}