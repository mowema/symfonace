<?php

namespace MenuBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MenuControllerTest extends WebTestCase
{
    public function testDefault()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/default');
    }

    public function testGeneratemenu()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/generateMenu');
    }

    public function testItems()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/items');
    }

}
