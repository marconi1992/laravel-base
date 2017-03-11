<?php


namespace App\Listeners;


use App\Contracts\Services\IActivationService;
use Illuminate\Auth\Events\Registered;

class SendActivationEmail
{
    /**
     * @var IActivationService
     */
    protected $activationService;

    public function __construct(IActivationService $activationService)
    {
        $this->activationService = $activationService;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $this->activationService->sendActivationMail($event->user);
    }
}