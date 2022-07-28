<?php

namespace App\Jobs;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailId;

    /**
     * Create a new job instance.
     * @param int $emailId
     * @return void
     */
    public function __construct(int $emailId)
    {
        $this->emailId = $emailId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = Email::find($this->emailId);
        if(!$email) {
            return;
        }
        //Send Email to selected recipients via SMTP service.
        $email->status = 1;
        $email->save();
    }
}
