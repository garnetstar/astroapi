<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2.7.16
 * Time: 21:40
 */

namespace App\Model\Repository;

use Nette\Security\Passwords;
use Tracy\Debugger;

class UserRepository extends AbstractRepository
{

    public function getUser($login, $clientId)
    {
        $user = $this->database->query('SELECT user_id, client_id FROM `user` WHERE login=? AND client_id=?', $login, $clientId);
        return $user->fetch();
    }

    /**
     * @param $accessToken
     * @return integer
     */
    public function getUserIdByAccessToken($accessToken) {
        return $this->database->query('SELECT `user_id` FROM `access_token` WHERE `access_token`=?', $accessToken)->fetch()['user_id'];
    }

} 