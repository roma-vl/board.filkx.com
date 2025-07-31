<?php

namespace App\Mail;

use App\Models\Newsletters\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newsletter;

    public $locale;

    public function __construct(Newsletter $newsletter, string $locale)
    {
        $this->newsletter = $newsletter;
        $this->locale = $locale;
    }

    public function build()
    {
        if ($this->newsletter->html) {
            return $this->subject($this->newsletter->title)
                ->view('emails.newsletter')
                ->with([
                    'newsletter' => $this->newsletter,
                    'locale' => $this->locale,
                ]);
        }

        return $this->subject($this->newsletter->title)
            ->markdown('emails.newsletter')
            ->with([
                'newsletter' => $this->newsletter,
                'locale' => $this->locale,
            ]);
    }
}
