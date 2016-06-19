<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 18.6.16
 * Time: 7:11
 */

namespace App\Model;


use App\lib\KeyGenerator\KeyGeneratorInterface;
use Nette\Database\Context;

class OAuthRepository
{

    /** @var  Context */
    private $database;

    /** @var  KeyGeneratorInterface */
    private $keyGenerator;

    public function __construct(Context $database, KeyGeneratorInterface $keyGenerator)
    {
        $this->database = $database;
        $this->keyGenerator = $keyGenerator;
    }

    public function getToken($login, $password, $clientId)
    {
        $content = $this->database->query('SELECT user_id FROM `user` WHERE login=? AND password=? AND client_id=?', $login, $password, $clientId);
        if (!$data = $content->fetch()) {
            return false;
        }

        $token = $this->keyGenerator->getKey();

        $this->database->query('INSERT INTO `token`', [
            'client_id' => $clientId,
            'user_id' => $data->user_id,
            'token' => $token
        ]);

        return $token;
    }

    public function authorize($accessToken)
    {
        $user = $this->database->query('SELECT user_id, client_id FROM `token` WHERE token=?', $accessToken)->fetch();

       return $user;
    }

} 