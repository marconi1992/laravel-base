<?php

namespace  App\ViewModels;

use App\User;

class UserActivationViewModel
{

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $link;
    /**
     * UserVerificationViewModel constructor.
     * @param User $user
     * @param $link
     */
    function __construct(User $user, $link)
    {
        $this->user = $user;
        $this->link = $link;
    }
}