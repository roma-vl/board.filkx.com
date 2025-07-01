<?php

namespace App\Console\Commands;

use App\Mail\NewsletterMail;
use App\Models\Newsletters\Newsletter;
use App\Models\Users\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendScheduledNewsletters extends Command
{
    protected $signature = 'newsletters:send';

    protected $description = 'Send all scheduled newsletters';

    public function handle()
    {
        $newsletters = Newsletter::where('sent', 0)
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($newsletters->isEmpty()) {
            $this->info('Немає новин для відправки.');

            return;
        }

        foreach ($newsletters as $newsletter) {
            $this->info("Відправляю: {$newsletter->title}");

            User::query()
                ->active()
                ->whereNotNull('email')
                ->where('email', 'drakyla60@gmail.com')
                ->chunk(100, function ($users) use ($newsletter) {
                    foreach ($users as $user) {
                        Mail::to($user->email)->send(new NewsletterMail($newsletter, $user->locale ?? config('app.locale')));
                    }
                });

            $newsletter->update(['sent' => 1]);

        }

        $this->info('Всі розсилки надіслано!');
    }
}
