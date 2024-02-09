<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Service\LoremIpsumGenerator;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface 
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    public function getDependencies() {
        return [
            UserFixtures::class,
        ];
    }
    public function load(ObjectManager $manager): void
    {

        $fc = new LoremIpsumGenerator();
        $uRepo = $manager->getRepository(User::class);
        $allUsers = $uRepo->findAll();
        
        for($i = 0; $i < 30; $i++) {
            if(random_int(0, 10) < 8) {
                $user = null;
            } else {
                $user = $allUsers[random_int(0, count($allUsers) - 1)];
            }

            $task = new Task;
            $task->setCreatedAt(new \DateTimeImmutable);
            $task->setAuthor($user);

            $task->setIsDone((bool)random_int(0, 1));
            $task->setContent($fc->generateLorem());
            $task->setTitle("Task Number ".$i);
            $manager->persist($task);
            if($user){
                $user->addTask($task);
                $manager->persist($user);
            }


        }
        $manager->flush();
    }


}
