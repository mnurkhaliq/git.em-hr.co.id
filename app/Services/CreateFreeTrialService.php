<?php
namespace App\Services;
use App\Models\ClientExport;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class CreateFreeTrialService
{
    public function handle($request)
    {
        ini_set('max_execution_time', 3000); //300 seconds = 5 minutes

        $nama              = $request['nama'];
        $email             = $request['email'];
        $jabatan           = $request['jabatan'];
        $bidang_usaha      = $request['bidang_usaha'];
        $nama_perusahaan   = $request['nama_perusahaan'];
        $handphone         = $request['handphone'];

        $destination = storage_path('app');
        $name_excel = 'Request_Trial'.date('YmdHis');
        $file = $destination ."//". $name_excel.'.xlsx';

        Excel::store(new ClientExport($nama, $email, $jabatan, $bidang_usaha, $nama_perusahaan, $handphone), $name_excel.'.xlsx');
        //  return (new ClientExport($nama, $email, $jabatan, $bidang_usaha, $nama_perusahaan, $handphone))->download('Request-trial'.date('d-m-Y') .'.xlsx');

        $params['text']     = 'Free Trial Request';
        $emailto = ['marketing@empore.co.id'];
        \Mail::send('email.trial-account', $params,
            function($message) use($request, $file, $email, $emailto,$name_excel, $destination) {
                $message->to($emailto);
                $message->subject('Request Trial');
                $message->attach($file, array(
                        'as' => $name_excel .'.xlsx',
                        'mime' => 'application/xlsx'
                    )
                );
            }
        );
        Config::set('database.default','mysql');
        $params = getEmailConfig();
        $params['view']     = 'email.request-free-trial';
        $params['subject']  = get_setting('mail_name').' - Em-HR Free Trial Request';
        $params['name']     = $nama;
        $params['email']    = $email;
        info($params);
        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
        dispatch($job);
        Config::set('database.default',session('db_name','mysql'));

        return true;
    }
}