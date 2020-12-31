<?php

namespace App\Mail;

use App\Models\RequestPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestPaymentCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $requestPayment;
    public $fileName;
    public $destination;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(RequestPayment $requestPayment)
    {
        //
        $this->requestPayment = $requestPayment;
        $this->fileName = $this->requestPayment->jmbg. '_'. $this->requestPayment->payment->file;
        $this->destination = storage_path() . '/pdf/downloaded/' . $this->fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.payment.created')
            ->subject('Platni listić ' .$this->fileName)
            ->attach($this->destination);
    }
}
