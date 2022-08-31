<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\BirthdayWording;
use Illuminate\Http\Request;

class BirthdayWordingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BirthdayWording::get();

        return view('administrator.birthday-wording.index', compact('data'));
    }

    public function store(Request $request)
    {
        $word = BirthdayWording::updateOrCreate(
            ['word' => $request->word],
            ['word' => $request->word]
        );

        return redirect()->route('administrator.birthday-wording.index')->with('message-success', 'Data saved successfully !');
    }

    public function destroy($id)
    {
        BirthdayWording::destroy($id);

        return redirect()->route('administrator.birthday-wording.index')->with('message-success', 'Data deleted successfully !');
    }
}
