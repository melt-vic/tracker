<?php

namespace App\Controller;

use App\Service\TaskService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends AbstractController
{

    /**
     * @Route("/overview", name="overview")
     */
    public function index(TaskService $taskService): Response
    {
        return $this->render('tracker/overview.html.twig', [
            'tasks' => $taskService->getOverview()
        ]);
    }
}
