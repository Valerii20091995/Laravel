protected function schedule(Schedule $schedule)
{
  $schedule->command('create-yougile-tasks')->everyMinute();
}
