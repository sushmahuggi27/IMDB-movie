<?php

namespace App\Event\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LogsRepository;
use App\Entity\Admin;
use App\Entity\Logs;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdminLogListener
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
        if($this->tokenStorage->getToken() != null) {
            $user= $this->tokenStorage->getToken()->getUser();
            $date = new \Datetime();
            $action = $event->getRequest()->attributes->get('_controller');
            $method =  $event->getRequest()->getMethod();
            $userLogRepository = $this->entityManager->getRepository(Logs::class);

            if($user instanceof Admin) {
                $userLog = new Logs();
                $userLog->setAdminMail($user->getEmail());
                $userLog->setAction($action);
                $userLog->setMethod($method);
                $userLog->setDate($date);
                if($event->getRequest()->request && $method=='POST') {
                    $userLog->setLogata(json_encode($event->getRequest()->request->all()));
                } else {
                    $userLog->setLogdata($event->getRequest()->getPathInfo());
                }
                $this->entityManager->persist($userLog);
                $this->entityManager->flush();
            }   
        }
    }
}

?>