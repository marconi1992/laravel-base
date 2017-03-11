<?php

namespace App\Contracts\Services;


interface IActivationService
{

    /**
     * @param USer $user
     */
    public function sendActivationMail($user);

    /**
     * @param $token
     * @return null|User
     */
    public function activateUser($token);
}