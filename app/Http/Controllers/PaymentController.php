<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $payments = Payment::all();

        return response($payments);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'file' => 'required|file|mimes:pdf'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $payment = Payment::where('file',$request->file('file')->getClientOriginalName())->first();

        if(!Gate::allows('create',Payment::class)){
            return response(['errors' => 'Samo admin user moze da postavi nove listiće']);
        }

        if($payment !== null){
            return response(['errors' => 'Listić pod tim imenom već postoji u našoj bazi'],422);
        }
        $file = $request->file('file');
        $payment = new Payment();
        $payment->file = $file->getClientOriginalName();
        $destination = storage_path() . '/pdf';
        $file->move($destination, $file->getClientOriginalName());

        $payment->save();
        return response(["message" => 'Payment list successfully uploaded']);
    }


}
