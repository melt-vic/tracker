<?php

namespace App\Controller;

use App\Form\TaskType;
use App\Entity\Task;
use App\Service\TaskService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrackerController extends AbstractController
{

    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    /**
     * @Route("/", name="tracker", methods={"GET", "HEAD"})
     */
    public function index(TaskService $taskService): Response
    {
        $oForm = $this->createForm(TaskType::class, new Task());

        return $this->render('tracker/index.html.twig', [
            'form' => $oForm->createView(),
            'task' => $taskService->getWorkingOnTask()
        ]);
    }

    /**
     * @Route("/", name="processForm", methods={"POST"})
     */
    public function processForm(Request $request, TaskService $taskService)
    {
        $eTask = new Task();
        $oForm = $this->createForm(TaskType::class, $eTask);
        $oForm->handleRequest($request);
        if ($oForm->isSubmitted() && $oForm->isValid()) {
            $eTask = $taskService->getOrAddTask($oForm->get('name')->getData());
            $request->getSession()->getFlashBag()->add('success', 'Tracking task!');
        } else {
            $eTask = null;
        }

        return $this->render('tracker/index.html.twig', [
            'form' => $oForm->createView(),
            'task' => $eTask
        ]);
    }

    /**
     * @Route("/stop-task/{sTaskId}", name="stopTask")
     */
    public function stopTask(string $sTaskId, TaskService $taskService): Response
    {
        if ($taskService->stopTask($sTaskId)) {
            return new JsonResponse(true);
        }

        return new JsonResponse('Please, don\'t be naughty with this innocent app');
    }
}
