<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;

    public $address;

    public function __construct($subscription, $address)
    {
        $this->subscription = $subscription;
        $this->address = $address;
    }

    public function build()
    {
        $pdf = Pdf::loadView('invoices.subscription', [
            'subscription' => $this->subscription,
            'address' => $this->address,
        ])->setPaper('A4', 'portrait');

        return $this->subject('Your Subscription Invoice')
            ->view('emails.subscription')
            ->attachData($pdf->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with([
                'subscription' => $this->subscription,
                'address' => $this->address,
            ]);
    }
}
