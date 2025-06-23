<?php

namespace App\Jobs;

use App\Mail\LoginAlertMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('mail_logs')->info('Mail sent', [
            'to' => $this->user->email,
            'subject' => 'Login Alert',
            'timestamp' => now(),
        ]);

        Mail::to($this->user->email)->send(new LoginAlertMail($this->user));

    }

}
