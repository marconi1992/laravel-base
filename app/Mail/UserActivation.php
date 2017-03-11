<?php

namespace App\Mail;

use App\User;
use App\ViewModels\UserActivationViewModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActivation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var UserActivationViewModel
     */
    public $model;

    /**
     * Create a new message instance.
     * @param UserActivationViewModel $model
     */
    public function __construct(UserActivationViewModel $model)
    {
        $this->model = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.activation');
    }
}
