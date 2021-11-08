<?php

namespace App\Jobs;

use App\Mail\SendInvoiceEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use PDF;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $invoice->load(['invoiceDetails','customerProfile']);
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data['invoice'] = $this->invoice;
        $data['subject'] = $this->invoice['title'];
        $data['pdf'] = PDF::loadView('admin.invoice.invoiceMail', $data);
        
        $email = new SendInvoiceEmail($data);
              
        Mail::to($this->invoice->customerProfile['email'])->send($email);
        Mail::mailer('mailgun')->to($this->invoice->customerProfile['email'])->send($email);
    }
}
