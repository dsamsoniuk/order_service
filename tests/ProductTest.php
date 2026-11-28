<?php

namespace App\Tests;

use App\Factory\ProductFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProductTest extends WebTestCase
{
    use Factories, ResetDatabase;
    public function testProductList(): void
    {

        $client = static::createClient();
        ProductFactory::createMany(15);

        $client->request('GET', '/product/list');
        $this->assertResponseIsSuccessful();


        $json = $client->getResponse()->getContent();
        $data = json_decode($json, true);

        self::assertCount(10, $data['products']);
    }

    public function testProductShow(): void {
        $client = static::createClient();
        $product = ProductFactory::createOne([
            'name' => 'Bluza niebieska',
            'description' => 'Wysokiej jakości bluza',
            'price' => 300,
        ]);
        $pdotuctId = $product->getId();

        $client->request('GET', "/product/show/$pdotuctId");
        $this->assertResponseIsSuccessful();

        $json = $client->getResponse()->getContent();
        $data = json_decode($json, true);

        self::assertSame('Bluza niebieska', $data['name']);
    }

    public function testProductUpdate(): void {
        $client = static::createClient();
        $product = ProductFactory::createOne([
            'name' => 'Bluza niebieska',
            'description' => 'Wysokiej jakości bluza',
            'price' => 300,
        ]);
        $pdotuctId = $product->getId();

        $client->jsonRequest('POST', "/product/update/$pdotuctId", [
            // 'name' => 'Czerwona bluza'
            'name' => ''
        ]);
        // $this->assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(422);

        $json = $client->getResponse()->getContent();
        $data = json_decode($json, true);
        dump($data);
        // self::assertSame('Bluza niebieska', $data['name']);
    }


}
