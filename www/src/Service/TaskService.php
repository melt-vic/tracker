<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManager;

class TaskService
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getWorkingOnTask(): ?Task
    {
        return $this->em->getRepository(Task::class)->findOneBy(['workingOnIt' => true]);
    }

    public function getOrAddTask(string $name, \DateTime $dtStartedAt = null): Task
    {
        $dtStartedAt = $dtStartedAt ?? new \DateTime();

        return $this->em->getRepository(Task::class)->getTask($name, $dtStartedAt);
    }

    public function stopTask(string $name, \DateTime $dtStoppedAt = null): bool
    {
        $eTask = $this->em->getRepository(Task::class)->find($name);
        if ($eTask === null) {
            return false;
        }

        $dtStoppedAt = $dtStoppedAt ?? new \DateTime();
        $eTask->setStoppedAt($dtStoppedAt);
        $eTask->setTotalTime(
            $eTask->getTotalTime() + ($dtStoppedAt->getTimestamp() - $eTask->getStartedAt()->getTimestamp())
        );
        $eTask->setWorkingOnIt(false);
        $this->em->persist($eTask);
        $this->em->flush($eTask);

        return true;
    }

    public function getOverview(): ?array
    {
        $aOverview['tasks'] = $this->em->getRepository(Task::class)->findAll();
        if ($aOverview['tasks'] === null) {
            return null;
        }
        $aOverview['todayTime'] = $this->em->getRepository(Task::class)->getTodayTotalTime();

        return $aOverview;
    }

}