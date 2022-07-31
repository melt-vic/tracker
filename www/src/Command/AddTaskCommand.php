<?php

namespace App\Command;

use App\Service\TaskService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @todo Add nice validation
 */
class AddTaskCommand extends Command
{
    private TaskService $taskService;

    protected static $defaultDescription = 'Report or update a new task.';
    protected static $defaultName = 'app:addTask';

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Task name');
        $this->addArgument(
            'startedAt',
            InputArgument::REQUIRED,
            'Task start date and time in format \'Y-m-d H:i:s\'. For instance 2022-07-30 10:40:00'
        );
        $this->addArgument(
            'stoppedAt',
            InputArgument::REQUIRED,
            'Task end date and time in format \'Y-m-d H:i:s\'. For instance 2022-07-30 11:40:00'
        );
    }

    private function isAlreadyWorking(OutputInterface $output)
    {
        $formattedBlock = $this->getHelper('formatter');

        $eTask = $this->taskService->getWorkingOnTask();
        if ($eTask !== null) {
            $aErrMessges = [
                'Oops!',
                'You are already working on a task! Please, stop it before reporting a new one. Take a look here: http://localhost:8080/public/'
            ];
            $formattedBlock = $formattedBlock->formatBlock($aErrMessges, 'comment');
            $output->writeln($formattedBlock);

            return true;
        }

        return false;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->isAlreadyWorking($output)) {
            return Command::FAILURE;
        } else {
            $io = new SymfonyStyle($input, $output);
            $name = $input->getArgument('name');
            $startedAt = \DateTime::createFromFormat('Y-m-d H:i:s', $input->getArgument('startedAt'));
            $stoppedAt = \DateTime::createFromFormat('Y-m-d H:i:s', $input->getArgument('stoppedAt'));
            $this->taskService->getOrAddTask($name, $startedAt);
            $this->taskService->stopTask($name, $stoppedAt);
            $io->success('Task time recorded!');

            return Command::SUCCESS;
        }
    }
}