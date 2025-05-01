<?php

namespace App\Console\Commands;

use App\Jobs\SendHttpRequest;
use App\Models\Order;
use Illuminate\Console\Command;

class CreateYougileTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-yougile-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // запрашиваем из бд yougile укоторый поле это null
         $orders = Order::whereNull('yougile_task_id')->get();
            foreach ($orders as $order) {
              SendHttpRequest::dispatch($order);
            }
    }
}
