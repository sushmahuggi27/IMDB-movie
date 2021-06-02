<?php

namespace App\Event\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserlogsRepository;
use App\Entity\User;
use App\Entity\Userlogs;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserLogListener
{
    
    protected $tokenStorage;
    protected $entityManager;

    public function __construct(TokenStorageInterface  $tokenStorage, EntityManagerInterface $entityManager)
    {
        
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;

    }
    public function onKernelRequest(RequestEvent $event)
    {
        if( $this->tokenStorage->getToken() != null){
            $user= $this->tokenStorage->getToken()->getUser();
            $date = new \Datetime();
            $action = $event->getRequest()->attributes->get('_controller');
            $method =  $event->getRequest()->getMethod();
            $userLogRepository = $this->entityManager->getRepository(Userlogs::class);

            if($user instanceof User){
                $userLog = new Userlogs();
                $userLog->setUserEmail($user->getEmail());
                $userLog->setAction($action);
                $userLog->setMethod($method);
                $userLog->setDate($date);
                $this->entityManager->persist($userLog);
                $this->entityManager->flush();
            }
        }

    }
}

?>