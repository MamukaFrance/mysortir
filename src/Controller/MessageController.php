<?php

namespace App\Controller;

use App\Service\DelayedTaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function sendMessage(DelayedTaskService $service)
    {
        $result = $service->sendDelayedTask("Mon message personnalisé", 10000); // 10s pour test
        if ($result) {
            $this->addFlash('success', $result);
        }else{
            $this->addFlash('error', 'Un problème est survenu...');
        }



        return $this->redirectToRoute('main_home');
    }
}
