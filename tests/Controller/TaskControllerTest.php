<?php

namespace App\Tests;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTaskList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Liste des tâches a faire");
        $this->assertSelectorExists('.card-content');
    } 
    public function testCreateTask(): void
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email'=>'myEmail1@website.com']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);


        //seulement utilisateur connecté
        $crawler = $client->request('GET', '/task/create');
        $this->assertResponseIsSuccessful();
        $buttonCrawlerNode = $crawler->selectButton('Save');

        $formValues = [
            'task[title]' => 'Title testCreateTask',
            'task[content]' => 'Contenu TEST'
        ];



        $form = $buttonCrawlerNode->form($formValues);

        $client->submit($form);

        $this->assertResponseRedirects('/task/');


    }
    
    public function testEditTask(): void
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email'=>'myEmail1@website.com']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testTask = $taskRepository->findOneBy(['title'=>'Title testCreateTask']);

        $taskId = $testTask->getId();
        $req = '/task/'.$taskId.'/edit';
        


        //seulement utilisateur connecté
        $crawler = $client->request('GET', '/task/'.$taskId.'/edit');
        $this->assertResponseIsSuccessful();
        $buttonCrawlerNode = $crawler->selectButton('Update');

        $formValues = [
            'task[title]' => 'Title testEditTask',
            'task[content]' => 'Contenu TEST two'
        ];



        $form = $buttonCrawlerNode->form($formValues);

        $client->submit($form);

        $this->assertResponseRedirects('/task/');


    }

    public function testDeleteTask(): void
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email'=>'myEmail1@website.com']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);



        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $testTask = $taskRepository->findOneBy(['title'=>'Title testEditTask']);

        $taskId = $testTask->getId();

        $crawler = $client->request('POST', '/task/'.$taskId.'/delete');

        $this->assertResponseRedirects('/task/');

    } 
}
