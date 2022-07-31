This is a task tracker developed with Symfony 6.

In the main page you can begin to track a new task or an existing one. If you are already
working on a task, it will appear automatically. A timer and a stop button will appear, too.
Use this button to stop the task at any time. You can only work at one task at a time,
therefore, If you begin to track a different task when the timer is already running, the old 
task will stop and update the time spent. If you have an active task and leave the page 
before stopping it, the time will continue counting.

Following the "Overview" link in the navigation bar, you will see a report of all the tasks
you have worked on, with the total hours you worked today at the report's end.

The same options that are available via web, are also available via the command line. There
are two commands:

- php bin/console **app:addTask** This command expects three mandatory parameters:
  - The task name
  - When the task began in the format Y-m-d H:i:s
  - When the task ended in the format Y-m-d H:i:s

For instance:

    php bin/console app:addTask "Fixing bugs" '2022-07-30 20:15:00' '2022-07-30 20:55:00'
  
- php bin/console **app:overviewTasks** This command returns a report with the same information
available in the website.

Those commands must be executed inside the Docker container named "www":

`docker exec -it www bash`
