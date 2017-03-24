<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2.7.16
 * Time: 21:40
 */

namespace App\Model\Repository;

use App\lib\KeyGenerator\KeyGeneratorInterface;
use Nette\Security\Passwords;
use Tracy\Debugger;

class UserRepository extends AbstractRepository
{
    const CLIENT_GOOGLE = 2;
    const CLIENT_ASTRO = 1;

    public function getUser($login, $clientId)
    {
        $user = $this->database->query('SELECT user_id, client_id FROM `user` WHERE login=? AND client_id=?', $login, $clientId);
        return $user->fetch();
    }

    public function getUserById($userId)
    {
        $user = $this->database->query('SELECT * FROM `user` WHERE user_id=?', $userId);
        return $user->fetch();
    }

    /**
     * @param $accessToken
     * @return int
     */
    public function getUserIdByAccessToken($accessToken)
    {
        return $this->database->query('SELECT `user_id` FROM `access_token` WHERE `access_token`=?', $accessToken)->fetch()['user_id'];
    }

    /**
     * @param $login
     * @param $clientId
     * @return int
     */
    public function getUserIdByLogin($login, $clientId)
    {
        return $this->database->query('SELECT `user_id`
        FROM `user`
        WHERE `login`=? AND client_id=?',
            $login, $clientId)->fetch()['user_id'];
    }

    /**
     * @param $firstName
     * @param $surname
     * @param $login
     * @param $password
     * @param $clientId
     * @param null $userId
     * @return \Nette\Database\ResultSet
     */
    public function addUser($firstName, $surname, $login, $password, $clientId, $userId = null)
    {
        $res = $this->database->query('INSERT INTO `user`', [
            'user_id' => $userId,
            'surname' => $surname,
            'first_name' => $firstName,
            'login' => $login,
            'password' => $password,
            'client_id' => $clientId,
        ]);

        return $this->database->getInsertId();
    }


}