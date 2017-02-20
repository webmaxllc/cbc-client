<?php

class SetupTests extends ClientTestCase
{
  public function testDefaultSetup()
  {
    $client = $this->createClient();

    $this->assertAttributeSame(true, 'debug', $client);
    $this->assertAttributeInstanceOf('GuzzleHttp\Client', 'client', $client);
    $this->assertAttributeSame(sys_get_temp_dir(), 'serializerCacheDirectory', $client);
  }

  public function testDebugSetup()
  {
    $client = $this->createClient(array(), null, true);

    $this->assertAttributeSame(true, 'debug', $client);
  }

  public function testCustomSerializerMetadataDirectory()
  {
    $client = $this->createClient(array(), __DIR__, false);

    $this->assertAttributeSame(__DIR__, 'serializerCacheDirectory', $client);
    $this->assertInstanceOf('JMS\Serializer\SerializerInterface', $client->getSerializer());
  }

  public function testLazySerializer()
  {
    $client = $this->createClient();

    $this->assertAttributeSame(null, 'serializer', $client);
    $this->assertInstanceOf('JMS\Serializer\SerializerInterface', $client->getSerializer());
  }

  public function testGuzzleClient()
  {
    $client = $this->createClient();

    $this->assertInstanceOf('GuzzleHttp\Client', $client->getGuzzleClient());
  }
}
