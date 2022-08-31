<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushTokens implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $notifData, $config;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notifData, $config)
    {
        $this->notifData = $notifData;
        $this->config = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = \FCMHelper::sendToDevice($this->notifData, $this->config);
        if ($response && method_exists($response, 'tokensToDelete')) {
            $tokensToDelete = $response->tokensToDelete();
            \FCMHelper::removeTokens($tokensToDelete);
        }
    }

    public function fail($exception = null)
    {
        info('error queue push tokens');
        $this->delete();
    }
}
