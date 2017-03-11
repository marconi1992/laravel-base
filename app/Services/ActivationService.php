<?php

namespace App\Services;

use App\Contracts\Repositories\IActivationRepository;
use App\Contracts\Services\IActivationService;
use App\Mail\UserActivation;
use App\User;
use App\ViewModels\UserActivationViewModel;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

class ActivationService implements IActivationService
{
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var IActivationRepository
     */
    protected $activationRepo;

    protected $resendAfter = 24;

    public function __construct(Mailer $mailer, IActivationRepository $activationRepo)
    {
        $this->mailer = $mailer;
        $this->activationRepo = $activationRepo;
    }

    /**
     * @param User $user
     */
    public function sendActivationMail($user)
    {
        if($user->activated || !$this->shouldSend($user)) {
            return;
        }

        $token = $this->activationRepo->createActivation($user->id);

        $link = route('user.activate', $token);
        
        $model = new UserActivationViewModel($user, $link);
        
        $this->mailer->to($user->email)->send(new UserActivation($model));
    }

    /**
     * @param $token
     * @return null|User
     */
    public function activateUser($token)
    {
        $activation = $this->activationRepo->getActivationByToken($token);

        if ($activation === null) {
            return null;
        }

        $user = User::find($activation->user_id);

        $user->activated = true;

        $user->save();

        $this->activationRepo->deleteActivation($token);

        return $user;

    }

    private function shouldSend($user)
    {
        $activation = $this->activationRepo->getActivation($user);
        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }
}