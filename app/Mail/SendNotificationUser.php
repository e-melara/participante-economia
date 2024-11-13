<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotificationUser extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $persona;
    public $password;

    public function __construct($persona, $password, $emailToPerson)
    {
        $this->persona = $persona;
        $this->password = $password;
        $this->email = $emailToPerson;
    }

    public function build()
    {
        return $this
            ->subject('Bienvenido a la plataforma de Aldea')
            ->view('emails.notification_user', [
                'email' => $this->email,
                'persona' => $this->persona,
                'password' => $this->password
            ]);
    }
}
