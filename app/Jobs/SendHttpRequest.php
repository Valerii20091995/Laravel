<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Clients\YougileClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use App\DTO\CreateTaskDTO;
use Illuminate\Support\Facades\Log;

class SendHttpRequest implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected Order $order;
    protected YougileClient $yougileClient;
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->yougileClient = new YougileClient();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Создание заказа #" . $this->order->id);

        try {
            $description = "Имя: {$this->order->name} <br>"
                . "Адрес: {$this->order->address} <br>"
                . "Телефон: {$this->order->phone} <br>"
                . "Комментарий: {$this->order->comment} <br>"
                . "Список Товаров: <br>";

            foreach ($this->order->products as $orderProduct) {
                $description .= "product_id: " . $orderProduct->id . "  "
                    . $orderProduct->name . " - " . $orderProduct->pivot->amount . " шт.<br>";
            }

            // Отправляем в Yougile
            $dto = new CreateTaskDTO(
                "Заказ #{$this->order->id}",
                "3972ae1f-e4d6-4963-b9c9-cd0fbba0b6ef",
                $description
            );

            $taskId = $this->yougileClient->createTask($dto);

            if ($taskId) {
                $this->order->yougile_task_id = $taskId;
                $this->order->save();
                Log::info("Тикет в Yougile создан", ['task_id' => $taskId]);
            }
        } catch (\Exception $e) {
            Log::error("Ошибка при создании тикета в Yougile", [
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
