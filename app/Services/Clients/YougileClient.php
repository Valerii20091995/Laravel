<?php

namespace App\Services\Clients;

use App\DTO\CreateTaskDTO;
use Illuminate\Support\Facades\Http;

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
    public function deleteTask(string $taskId)
    {
        $response = retry(3, function () use ($taskId) {
            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->delete($this->baseUrl . '/tasks/' . $taskId);
        });
    }

}
