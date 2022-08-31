<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\CreateFreeTrialRequest;
use App\Models\ConfigDB;
use App\Models\CrmProduct;
use App\Models\CrmBlog;
use App\Models\CrmPromo;
use App\Models\CrmBlogComment;
use App\Models\CrmFAQ;
use App\Services\CreateFreeTrialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\Cloner\Data;
use Illuminate\Routing\Controller as BaseController;
use App\Models\CrmSales;
use Session;

class LandingPageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        App::setLocale("id");
    }

    public function page1()
    {
        App::setLocale("id");
        $data = CrmBlog::where('is_publish', true)->where('is_publish_landing', true)->orderBy('create_date', 'DESC')->get();

        $data_promo = CrmPromo::where(function($query) {
            $query->whereNull('publish_end_date')->orWhere('publish_end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
        })->where(function($query) {
            $query->whereNull('publish_start_date')->orWhere('publish_start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'));
        })->orderBy('publish_start_date', 'ASC')->get();
        
        // dd($data_promo);
        $params['data'] = $data;
        $params['data_promo'] = $data_promo;
        return view('landing-page/home')->with($params);
    }

    public function feature()
    {
        $data = CrmProduct::where('is_published', true)->orderBy('name', 'ASC')->get();
        $data_blog = CrmBlog::where('is_publish', true)->where('is_publish_landing', true)->orderBy('create_date', 'DESC')->get();
        $data_blog_all = CrmBlog::where('is_publish', true)->orderBy('create_date', 'DESC')->get();
        
        // dd($data)
        $params['data'] = $data;
        $params['data_blog'] = $data_blog;
        $params['data_blog_all'] = $data_blog_all;

        return view('landing-page/feature')->with($params);
    }

    public function contact()
    {
        $data = CrmSales::where('is_publish_landingpage', true)->get();
        $params['data'] = $data;
        return view('landing-page/contact')->with($params);
    }

    public function subscribe()
    {
        return view('landing-page/howto_subscribe');
    }

    public function helpcenter()
    {
        $data = CrmFAQ::orderBy('create_date', 'ASC')->get();
        $params['data'] = $data;
        return view('landing-page/help_center')->with($params);
    }

    public function feature_detail($category, $id_feature)
    {
    
        $params['data']             = CrmProduct::where('id', $id_feature)->first();
        
        $datas = CrmBlog::where('is_publish', true)->where('category_id', $id_feature)->orderBy('create_date', 'DESC')->get();
        $params['datas'] = $datas;
        $params['section']          = 'news';
        $params['title']            = 'News';


        return view('landing-page/feature_detail')->with($params);
    }

    public function promo($title, $id)
    {
        $params['promo'] = CrmPromo::where('id', $id)->first();
        return view('landing-page/promo_detail')->with($params);
    }

    public function promo_list()
    {
        $data = CrmPromo::where(function($query) {
            $query->whereNull('publish_end_date')->orWhere('publish_end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
        })->where(function($query) {
            $query->whereNull('publish_start_date')->orWhere('publish_start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'));
        })->orderBy('publish_start_date', 'ASC')->get();
        $params['promos'] = $data;
        return view('landing-page/promo_all')->with($params);
    }

    public function detail($category, $title, $id, $id_article)
    {
    
        $currentURL = url()->current();
        Session::put('redirectUrl', $currentURL);

        $params['data_feature']             = CrmProduct::where('id', $id_article)->first();
        $params['data']             = CrmBlog::where('id', $id)->first();
        $params['data_comments'] = CrmBlogComment::where('blog_id', $id)->where('is_publish', true)->orderBy('create_date', 'DESC')->get();
        $params['comment_count'] = $params['data_comments']->count();
        // $params['data_comments_reply'] = CrmBlogComment::where('parent_id', $id)->get();
        $datas = CrmBlog::where('is_publish', true)->where('id', '!=', $id)->where('category_id', $id_article)->orderBy('create_date', 'DESC')->get();
        $params['datas'] = $datas;
        // $params['news_list_right']  = CrmBlog::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
        $params['section']          = 'news';
        $params['title']            = 'News';

        return view('landing-page/detail')->with($params);
    }

    public function share_detail($category, $title, $id, $id_article)
    {
    
        $params['data_feature']             = CrmProduct::where('id', $id_article)->first();
        $params['data']             = CrmBlog::where('id', $id)->first();
        $params['data_comments'] = CrmBlogComment::where('blog_id', $id)->where('is_publish', true)->orderBy('create_date', 'DESC')->get();
        $params['comment_count'] = $params['data_comments']->count();
        // $params['data_comments_reply'] = CrmBlogComment::where('parent_id', $id)->get();
        $datas = CrmBlog::where('is_publish', true)->where('id', '!=', $id)->where('category_id', $id_article)->orderBy('create_date', 'DESC')->get();
        $params['datas'] = $datas;
        // $params['news_list_right']  = CrmBlog::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
        $params['section']          = 'news';
        $params['title']            = 'News';

        return view('landing-page/detail')->with($params);
    }

    public function shareSocial($id, $title)
    {
        
        return view('share-social', compact('socialShare'));
    }

    public function checkCode(Request $request){
        $validator = Validator::make(request()->all(), [
            'code'  => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        $config = ConfigDB::where('company_code',strtolower($request->code))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
        if(!$config){
            return response()->json(['status' => 'failed', 'message' => __("landingpage.login.subtext_company_404")]);
        }
        else {
            return response()->json(['status' => 'success', 'message' => __("landingpage.login.subtext_company_success")]);
        }
    }

    /**
     * Store page 1
     * @return void
     */
    public function storePage1(CreateFreeTrialRequest $request,
                               CreateFreeTrialService $createFreeTrialService)
    {
        $createFreeTrialService->handle($request);
        return redirect()->route('landing-page1')->with('message-success', 'Thank you for being interested in our products and registering for trial licenses, we have received your data and we will contact you immediately for trial account information');
    }
    public function loginClient(Request $request) 
    {
        $this->validate($request,[
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        return view('landing-page/index');
    }

    public function downloadExcel($request)
    {



    
    /*    $styleHeader = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            ''
        ];
        $destination = public_path('storage\temp');
        $name_excel = 'Request_Trial'.date('YmdHis');
        $file = $destination ."\\". $name_excel.'.xls';

        \Excel::create($name_excel,  function($excel) use($params, $styleHeader){
            $excel->sheet('Customer',  function($sheet) use($params){
            
            $sheet->cell('A1:F1', function($cell) {
                     $cell->setFontSize(12);
                     $cell->setBackground('#EEEEEE');
                     $cell->setFontWeight('bold');
                     $cell->setBorder('solid');
                 });

            $borderArray = array(
                 'borders' => array(
                     'outline' => array(
                         'style' => \PHPExcel_Style_Border::BORDER_THICK,
                         'color' => array('argb' => 'FFFF0000'),
                     ),
                 ),
             );

             $sheet->fromArray($params, null, 'A1', true);

            });

             $excel->getActiveSheet()->getStyle('A1:F2')->applyFromArray($styleHeader);

        })->save('xls', $destination, true);

       $params['text']     = 'Test Free Trial';
        
        \Mail::send('email.trial-account', $params,
            function($message) use($request, $file, $name_excel, $destination) {
                $message->to('farros.jackson@gmail.com');
                $message->subject('Request Trial');
                $message->attach($file, array(
                    'as' => $name_excel .'.xls',
                    'mime' => 'application/xls'
                    )
            );
            }
        );  */

    }


}
 
