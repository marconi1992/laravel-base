<?php

namespace App\Repositories;

use App\Contracts\Repositories\IActivationRepository;
use Carbon\Carbon;
use Illuminate\Database\Connection;

class ActivationRepository implements  IActivationRepository
{

    /**
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    protected $table = 'user_activations';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    /**
     * @param int $userId
     * @return mixed
     */
    function createActivation($userId)
    {
        $activation = $this->getActivation($userId);

        if (!$activation) {
            return $this->createToken($userId);
        }

        return $this->regenerateToken($userId);
    }

    /**
     * @param int $userId
     * @return array|null|stdClass
     */
    function getActivation($userId)
    {
        return $this->db->table($this->table)->where("user_id", $userId)->first();
    }

    /**
     * @param string $token
     * @return array|null|\stdClass
     */
    public function getActivationByToken($token)
    {
        return $this->db->table($this->table)->where('token', $token)->first();
    }

    /**
     * @param string $token
     */
    public function deleteActivation($token)
    {
        $this->db->table($this->table)->where('token', $token)->delete();
    }

    /**
     * @return string
     */
    protected function getToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * @param int $userId
     * @return string
     */
    private function createToken($userId)
    {
        $token = $this->getToken();

        $this->db->table($this->table)->insert([
            'user_id' => $userId,
            'token' => $token,
            'created_at' => new Carbon()
        ]);

        return $token;
    }

    /**
     * @param int $userId
     * @return string
     */
    private function regenerateToken($userId)
    {
        $token = $this->getToken();

        $this->db->table($this->table)
            ->where("user_id", $userId)
            ->update([
                'token' => $token,
                'created_at' => new Carbon()
            ]);

        return $token;
    }
}