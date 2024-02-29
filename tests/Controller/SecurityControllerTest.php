<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
           '_username' => 'myEmail1@website.com',
           '_password' => 'FnuyAssword1'
        ]);
        $client->submit($form);
     
        $this->assertResponseIsSuccessful();

    } 
    public function testLogout(): void
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email'=>'myEmail1@website.com']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/logout');

        //seulement utilisateur connecté
        $crawler = $client->request('GET', '/task/create');

        $this->assertResponseRedirects('/login');

    } 
}
