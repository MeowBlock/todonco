<?php

namespace App\Tests;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function getTask() {
        return new Task();
    }
    public function testId()
    {
        $task = $this->getTask();
        
        $this->assertNull($task->getId());
    }
    public function testCreatedAt()
    {
        $task = $this->getTask();
        $date = new DateTimeImmutable();
        
        $task->setCreatedAt($date);
        $this->assertEquals($date, $task->getCreatedAt());
    }
    public function testEmail()
    {
        $task = $this->getTask();
        $title = "Le titre de ma tache";
        
        $task->setTitle($title);
        $this->assertEquals($title, $task->getTitle());
    }

    public function testContent()
    {
        $task = $this->getTask();
        $content = "le contenu de ma tache est beaucoup plus long, smilesmilesmilesmilesmilesmilesmilesmilesmilesmilesmilesmile
        smilesmilesmilesmilesmilesmilesmilesmilesmilesmilesmilesmilesmile ";
        
        $task->setContent($content);
        $this->assertEquals($content, $task->getContent());
    }
    public function testIsDone()
    {
        $task = $this->getTask();
        $isDone = true;
        
        $task->setIsDone($isDone);
        $this->assertTrue($task->isIsDone());
    }
    public function testAuthor()
    {
        $task = $this->getTask();
        $author = new User();
        
        $task->setAuthor($author);
        $this->assertEquals($author, $task->getAuthor());
    }
}
