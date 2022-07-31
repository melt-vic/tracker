<?php

namespace App\Command;

use App\Service\TaskService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OverviewCommand extends Command
{
    private TaskService $taskService;
    protected static $defaultDescription = 'Shows a report of all tasks.';
    protected static $defaultName = 'app:overviewTask';

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $aTasks = $this->taskService->getOverview();
        $io = new SymfonyStyle($input, $output);
        $aRows = [];

        if ($aTasks !== null) {
            foreach ($aTasks['tasks'] as $eTask) {
                $aRows[] = [
                    $eTask->getName(),
                    $eTask->getStartedAt()->format('d-m-Y H:i:s'),
                    empty($eTask->getStoppedAt()) ? '' : $eTask->getStoppedAt()->format('d-m-Y H:i:s'),
                    $eTask->getTotalTime(),
                    $eTask->isWorkingOnIt()
                ];
            }
            $io->title('Overview');
            $io->table(['Name', 'Started at', 'Last time', 'Total time', 'Working on it'],
                $aRows);
            $io->info('Total time today: ' . round($aTasks['todayTime']['totalTime'] / 60 / 60, 2) . ' h');
        }

        return Command::SUCCESS;
    }
}