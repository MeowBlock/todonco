<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    // public function setUp(): void {
    //     parent::setUp();
    //     $userRepository = static::getContainer()->get(UserRepository::class);

    //     $testUser = $userRepository->findOneBy(['email'=>'TestEmail2@test.com']);

    //     $entityManager = static::getContainer()->get(EntityManager::class);
    //     if($testUser) {
    //         $entityManager->remove($testUser)->flush();
    //     }

    // }
    public function testCreateUser(): void
    {
        $client = static::createClient();
        

        //seulement utilisateur connecté
        $crawler = $client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
        $buttonCrawlerNode = $crawler->selectButton('Save');

        $formValues = [
            'user[username]' => 'UsernameTest',
            'user[password][first]' => 'ligma',
            'user[password][second]' => 'ligma',
            'user[email]' => 'TestEmail@test.com',
        ];



        $form = $buttonCrawlerNode->form($formValues);

        $client->submit($form);

        $this->assertResponseRedirects('/users/');

        // $formValues = [
        //     'user[username]' => 'UsernameTest',
        //     'user[password][first]' => 'ligma',
        //     'user[password][second]' => 'fauxmotdepasse',
        //     'user[email]' => 'TestEmail@test.com',
        // ];



        // $form = $buttonCrawlerNode->form($formValues);

        // $client->submit($form);
    }
    
    public function testEditUser(): void
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email'=>'TestEmail@test.com']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $userId = $testUser->getId();
        


        //seulement utilisateur connecté
        $crawler = $client->request('GET', '/users/'.$userId.'/edit');
        $this->assertResponseIsSuccessful();
        $buttonCrawlerNode = $crawler->selectButton('Update');

        // $rand = random_int(0, PHP_INT_MAX);

        $formValues = [
            'user[username]' => 'UsernameTestUPDATE',
            'user[password][first]' => 'ligma',
            'user[password][second]' => 'ligma',
            'user[email]' => 'TestEmail2@test.com',
        ];



        $form = $buttonCrawlerNode->form($formValues);

        $client->submit($form);

        $this->assertResponseRedirects('/users/');


    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email'=>'TestEmail2@test.com']);
        // $testUser = $userRepository->findAll()[0];

        // simulate $testUser being logged in
        $client->loginUser($testUser);


        $userId = $testUser->getId();
        // ,[
        //     '_token'=>static::getContainer()->get('security.token')
        // ]

        $crawler = $client->request('POST', '/users/'.$userId.'/delete');

        $this->assertResponseRedirects('/users/');

    } 
}
