<?php


namespace App\Listeners;


use App\Contracts\Services\IActivationService;
use App\User;
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
     * @param  Registered $event
     * @throws \Exception
     */
    public function handle(Registered $event)
    {
        $user = User::where($event->user->getAuthIdentifierName(), $event->user->getAuthIdentifier())->first();

        if (!$user) {
            throw new \Exception("can not found user with " . $event->user->getAuthIdentifierName()
                ." '" . $event->user->getAuthIdentifier() . "'");
        }

        try {
            $this->activationService->sendActivationMail($user);
        } catch (\Exception $ex) {
            $user->delete();
            throw $ex;
        }
    }
}