<?php

namespace App\Contracts\Repositories;

interface IActivationRepository
{

    /**
     * @param int $userId
     * @return mixed
     */
    function createActivation($userId);

    /**
     * @param int $userId
     * @return array|null|stdClass
     */
    function getActivation($userId);

    /**
     * @param string $token
     * @return array|null|\stdClass
     */
    public function getActivationByToken($token);

    /**
     * @param string $token
     */
    public function deleteActivation($token);
}