<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function build()
    {
        return $this->view($this->view)
                    ->subject('Password Changed');
    }
} 