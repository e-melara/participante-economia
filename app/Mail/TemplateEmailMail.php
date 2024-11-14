<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TemplateEmailMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public string $template = '';
    public string $asunto = '';

    public array $data = [];

    public function __construct($template, $asunto = 'Asunto predeterminado', $data = [])
    {
      $this->data = $data;
      $this->asunto = $asunto;
      $this->template = $template;
    }

    public function build()
    {
        return $this
            ->subject($this->asunto)
            ->view($this->template, $this->data);
    }
}
