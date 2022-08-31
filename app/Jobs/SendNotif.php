<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $params;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $params = $this->params;
        $endpoint = env('FIREBASE_DATABASE_URL') . env('SERVER') . '/' . strtolower($params['company_code']) . '/' . $params['user_id'] . '.json';
        $client = new \GuzzleHttp\Client();

        while (($data = collect(json_decode($client->request('GET', $endpoint . '?print=pretty')->getBody()->getContents())))->count() >= env('FIREBASE_LIMIT', 30)) {
            $client->request('DELETE', env('FIREBASE_DATABASE_URL') . env('SERVER') . '/' . strtolower($params['company_code']) . '/' . $params['user_id'] . '/' . $data->keys()->first() . '.json');
        }

        return $client->request('POST', $endpoint, [
            'json' => json_decode(json_encode($params['notifArray'])),
        ]);
    }
    
    public function fail($exception = null)
    {
        info('error queue notif');
        $this->delete();
    }
}
