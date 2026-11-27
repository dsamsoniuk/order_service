<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{
    public function testBasicMain(): void
    {
        $client = static::createClient();
        $client->request('GET', '/main');

        $response = $client->getResponse()->getContent();

        $this->assertJson($response);
    }
}
