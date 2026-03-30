<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\User;
use App\Services\SubscriptionPricingService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class InvoicePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Payment $payment;
    public Collection $subscriptions;

    public function __construct(User $user, Payment $payment, Collection $subscriptions)
    {
        $this->user         = $user;
        $this->payment      = $payment;
        $this->subscriptions = $subscriptions;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Easy Cook Invoice' . (count($this->subscriptions) > 1 ? 's' : '') . ' #' . $this->payment->order_id
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-paid',
            with: [
                'user'          => $this->user,
                'payment'       => $this->payment,
                'subscriptions' => $this->subscriptions,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->subscriptions as $subscription) {
            $subscription->loadMissing(['coupon', 'couponV2', 'couponV2Usage', 'plan', 'user.addresses']);
            $pricing = app(SubscriptionPricingService::class)->calculateForStoredSubscription($subscription);

            $address = optional($subscription->user)->addresses()->primary()->first();

            // Pass EXACT keys used by your invoice view:
            $viewData = [
                'subscription'    => $subscription,
                'address'         => $address,
                'sub_total'       => $pricing['total_before_discount'],
                'discount'        => $pricing['discount_value'],
                'discount_label'  => $pricing['discount_label'],
                'discount_amount' => $pricing['discount_amount'],
                'after_discount'  => $pricing['total_after_discount'],
                'vat_rate'        => $pricing['vat_rate'],
                'vat_amount'      => $pricing['vat_amount'],
                'grand_total'     => $pricing['grand_total'],
                'list_with_vat'   => $pricing['list_with_vat'],
            ];

            try {
                $pdf = DomPDF::loadView('invoices.subscription', $viewData)
                    ->setPaper('a4', 'portrait');

                $attachments[] = Attachment::fromData(
                    fn () => $pdf->output(),
                    "invoice-{$subscription->id}.pdf"
                )->withMime('application/pdf');
            } catch (\Throwable $e) {
                \Log::error('Invoice PDF build failed in mailable', [
                    'subscription_id' => $subscription->id,
                    'message' => $e->getMessage(),
                ]);
                // Optional: skip this attachment but still send email (or rethrow to fail mail)
                continue;
            }
        }

        return $attachments;
    }

}
