<?php
class connectionTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->object = new \Etcd\Connection();
    }

    public function testGetServer()
    {
        $this->assertEquals('http://localhost:4001',$this->object->getServer());
    }

    public function testGetVersion()
    {
        $this->assertEquals(2,substr_count($this->object->getVersion(), '.'));
    }

    public function testGetAPIVersion()
    {
        $this->assertEquals(2,$this->object->getAPIVersion());
    }

    public function testGetAPI()
    {
        $api = $this->object->getAPI();
        $this->assertInstanceOf('\Etcd\API\V2',$api);
    }

    /**
     * @expectedException \Etcd\Exception\APIException
     */
    public function testSetAPIVersionInvalid()
    {
        $this->object->setAPIVersion(1);
    }
}
?>