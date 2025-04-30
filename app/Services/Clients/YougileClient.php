<?php

namespace App\Services\Clients;

use App\DTO\CreateTaskDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YougileClient
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.yougile.base_url');
        $this->apiKey = config('services.yougile.api_key');



    }
    public function createTask(CreateTaskDTO $createTaskDTO):?string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->baseUrl . '/tasks', $createTaskDTO->toArray());
    if (!$response->successful()) {
        throw new \Exception('Error creating task');
    }
    $data = $response->json();
    return $data['id'];
    }
    public function deleteTask(string $taskId):bool
    {
        try {
            $response = retry(3, function () use ($taskId) {
                return Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])->delete($this->baseUrl . '/tasks/' . $taskId);
            });
            if (!$response->successful()) {
                throw new \Exception('Error deleting task');
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Yougile,удаление тикета не получилось', [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

}
