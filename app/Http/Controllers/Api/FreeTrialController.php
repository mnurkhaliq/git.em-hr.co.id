<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateFreeTrialRequest;
use App\Services\CreateFreeTrialService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FreeTrialController extends Controller
{
    //
    public function create(CreateFreeTrialRequest $request, CreateFreeTrialService $createFreeTrialService){
        $request->validated();
        $createFreeTrialService->handle($request);
        return response()->json(
            [
                'status'    => 'success',
                'message'   => 'Thank you for register, please check your email!',
                'error'     => null
            ],
            200
        );
    }
}
