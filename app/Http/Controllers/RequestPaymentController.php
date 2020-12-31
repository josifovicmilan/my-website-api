<?php

namespace App\Http\Controllers;

use App\Mail\RequestPaymentCreated;
use App\Models\Payment;
use App\Models\RequestPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Debug;

class RequestPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $rules= [
            'email' => 'required|email',
            'jmbg' => 'required|digits:13',
            'payment' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $payment = Payment::where('id', $request->payment)->first();
        if(!$payment){
            return response(['error'=>'Sorry, we couldnt find that payment'], 404);
        }
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(storage_path() . '/pdf/' . $payment->file);

        $pages = $pdf->getPages();
        $br_strane = 1;
        $index = -1;
        foreach($pages as $page){
            $pos = strpos($page->getText(), $request->jmbg);
            if($pos > 0){
                $index = $br_strane;
            }
            $br_strane++;
        }
        if($index == -1){
            return response(["error"=>"JMBG not found"]);
        }
        $requestPayment = new RequestPayment([
            'jmbg' => $request->jmbg,
            'email' => $request->email,
            'payment_id' => $payment->id
         ]);
        $requestPayment->save();

        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->setSourceFile(storage_path() . '/pdf/' . $payment->file);
        $current_page = $pdf->importPage($index);
        $pdf->useTemplate($current_page, 10, 10, 200);
        $fileName = $request->jmbg. '_'. $payment->file;
        $destination = storage_path() . '/pdf/downloaded/' . $fileName;
        $pdf->Output($destination, 'F');
        Mail::to($request->email)->queue(new RequestPaymentCreated($requestPayment));
        return response(['message' => 'Na vašu email adresu je poslata poruka.']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequestPayment  $requestPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestPayment $requestPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestPayment  $requestPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestPayment $requestPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RequestPayment  $requestPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestPayment $requestPayment)
    {
        //
    }
}
