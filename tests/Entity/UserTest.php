<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function getUser() {
        return new User();
    }
    public function testUsername()
    {
        $user = $this->getUser();
        $name = "Test name";
        
        $user->setUsername($name);
        $this->assertEquals($name, $user->getUsername());
    }
    public function testEmail()
    {
        $user = $this->getUser();
        $name = "wawa@gmail.com";
        
        $user->setEmail($name);
        $this->assertEquals($name, $user->getEmail());
    }

    public function testPassword()
    {
        $user = $this->getUser();
        $password = "wa";
        
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testTask()
    {
        $user = $this->getUser();
        $task = new Task();
        
        $user->addTask($task);
        $this->assertContains($task, $user->getTask());
    }
    public function testRemoveTask() {
        $user = $this->getUser();
        $task = new Task();
        
        $user->addTask($task);
        $user->removeTask($task);
        $this->assertEmpty($user->getTask());
    }

    public function eraseCredentialsTest()
    {
        $user = $this->getUser();
        $user2 = $this->getUser();
        $user2->eraseCredentials();
        
        $this->assertEquals($user, $user2);
    }

    public function UserIdentifierTest()
    {
        $user = $this->getUser();
        $email = 'wawa@gmail.com';
        $user->setEmail($email);
        $test = $user->getUserIdentifier();
        
        $this->assertEquals($user->getEmail(), $test);
    }
}
