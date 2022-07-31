<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getTask(string $name, \DateTime $dtStartedAt): Task
    {
        $this->updateWorkingOnTask();

        $eTask = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();

        if ($eTask === null) {
            $eTask = new Task();
            $eTask->setName($name);
        }
        $eTask->setStartedAt($dtStartedAt);
        $eTask->setWorkingOnIt(true);
        $this->add($eTask, true);

        return $eTask;
    }

    public function add(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function updateWorkingOnTask()
    {
        $this->createQueryBuilder('t')
            ->update()
            ->set('t.workingOnIt', 0)
            ->where('t.workingOnIt = :workingOnIt')
            ->setParameter('workingOnIt', 1)
            ->getQuery()
            ->execute();
    }

    /**
     * If a task started before midnight, we consider it belongs to today
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTodayTotalTime()
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.totalTime) AS totalTime')
            ->where('DATE(t.stoppedAt) = DATE(NOW())')
            ->getQuery()
            ->getOneOrNullResult();
    }

}
