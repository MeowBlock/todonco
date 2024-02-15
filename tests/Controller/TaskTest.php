<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/');
        $this->assertResponseIsSuccessful();


    }
    public function testCreate(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/create');

        $this->assertResponseIsSuccessful();
    }
    public function testShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/');

        $this->assertResponseIsSuccessful();
    }
    public function testEdit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/');

        $this->assertResponseIsSuccessful();
    }
    public function testDelete(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/');

        $this->assertResponseIsSuccessful();
    }
}
