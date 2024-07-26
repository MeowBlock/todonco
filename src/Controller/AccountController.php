<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/mon_compte', name: 'app_account')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $toget = ['User'=> User::class, 'Task'=> Task::class];
        $toset = [];

        foreach($toget as $name=> $class) {
            
            $repo = $entityManager->getRepository($class);
            $el = $repo->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
            $toset[$name] = $el;
        }

        $alltasks = $entityManager->getRepository(Task::class)->findBy([], ['createdAt' => 'DESC'], 10);
        if($this->getUser()->isAdmin()) {
            $allusers = $entityManager->getRepository(User::class)->findAll();
        } else {
            $allusers = false;
        }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'user' => $this->getUser(),
            'users' => $toset['User'],
            'tasks' => $toset['Task'],
            'isAdmin' => $this->getUser()->isAdmin(),
            'alltasks' => $alltasks,
            'allusers' => $allusers,
        ]);
    }
}
