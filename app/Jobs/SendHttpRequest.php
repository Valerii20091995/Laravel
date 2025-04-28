<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendHttpRequest implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected string $url = "https://yougile.com/api-v2/tasks";
    protected array $headers = [
        'Authorization' => "Bearer FyfUN5DPJi-eRUuTHu7dGkXh41EYlXTGIfhcoYDnOcQkJemAsHdiFta9mZ-2bO9X",
        'Content-Type' => "application/json"
    ];
    public function __construct(array $body)
    {
        $this->body = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Http::withHeaders($this->headers)->post($this->url, $this->body);
    }
}
