<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use App\Mail\TemplateEmailMail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $asunto;

    public $template;

    public $data;


    public function __construct($data = array())
    {
      $this->email = $data['email'];
      $this->asunto = $data['asunto'];
      $this->template = $data['template'];
      $this->data = $data['data'];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      Mail::to($this->email)
        ->queue(new TemplateEmailMail(
          $this->template,
          $this->asunto,
          $this->data
        ));
    }
}
