<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $invoices;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->invoices = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->invoices['subject'])
        ->attachData($this->invoices['pdf']->output(), "invoice.pdf")
        ->view('admin.invoice.invoiceMail',$this->invoices);
    }
}
