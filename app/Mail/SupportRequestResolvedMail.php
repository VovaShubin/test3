<?php

namespace App\Mail;

use App\Models\SupportRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportRequestResolvedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public SupportRequest $supportRequest)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Your request has been resolved')
            ->view('emails.support_request_resolved');
    }
}


