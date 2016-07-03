<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2.7.16
 * Time: 20:55
 */

namespace App\Model\Repository;


class AccessTokenRepository extends AbstractRepository
{

    public function addAccessToken($accessToken, $userId, $clientId, $expire)
    {
        $this->database->query('INSERT INTO `access_token`', [
            'access_token' => $accessToken,
            'user_id' => $userId,
            'client_id' => $clientId,
            'expire' => $expire,
        ]);

    }

    public function getAccessToken($accessToken)
    {
        $token = $this->database->query('SELECT user_id, client_id FROM `access_token` WHERE access_token=? AND expire>NOW()', $accessToken);

        return $token->fetch();
    }
}