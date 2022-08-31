<?php

namespace App\Jobs;

use App\Mail\KpiMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

class SendEmail implements ShouldQueue
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
        //
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $params = $this->params;
        if(empty($params['mail_username']) || empty($params['email'])){
            info('salah1');
            $this->delete();
        }
        else{
            $transport = (new \Swift_SmtpTransport($params['mail_host'], $params['mail_port']))
                ->setEncryption($params['mail_encryption'])
                ->setUsername($params['mail_username'])
                ->setPassword($params['mail_password'])
                ->setStreamOptions(Config::get('mail.stream'));

            $mailer = app(\Illuminate\Mail\Mailer::class);
            $mailer->setSwiftMailer(new \Swift_Mailer($transport));

            $mailer->send($params['view'], $params, function ($message) use ($params) {
                $message->from($params['mail_username'],$params['mail_name']);
                $message->to($params['email']);
                $message->subject($params['subject']);
                // if(isset($params['image'])) {
                //     $message->attachData(base64_decode($params['image']), $params['image_as'], [
                //         'mime' => $params['image_mime']
                //     ]);
                // }
                if(isset($params['cc'])) {
                    $message->cc($params['cc']);
                }
                if(isset($params['bcc'])) {
                    $message->bcc($params['bcc']);
                }
            });
        }
    }
    public function fail($exception = null)
    {
        info('error queue mail');
        $this->delete();
    }
}
