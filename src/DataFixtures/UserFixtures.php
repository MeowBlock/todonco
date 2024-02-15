<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture  
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        for($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUsername('User '.$i);
            $user->setEmail('myEmail'.$i.'@website.com');
            random_int(0, 10) < 2 ? $user->setRoles(['ROLE_ADMIN']) : '';
            $plaintextPassword = 'FnuyAssword'.$i;

            $hashedPassword = $this->hasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);

        }
        $manager->flush();
    }


}
