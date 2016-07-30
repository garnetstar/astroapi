<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2.7.16
 * Time: 20:55
 */

namespace App\Model\Repository;


class RefreshTokenRepository extends AbstractRepository
{

    public function getRefreshToken($clientId, $refreshToken)
    {
        $token = $this->database->query('SELECT * FROM `refresh_token` WHERE client_id=? AND refresh_token=? AND expire > NOW()', $clientId, $refreshToken);
        return $token->fetch();
    }

    public function addRefreshToken($refreshToken, $userId, $clientId, $expire)
    {
        $this->database->query('INSERT INTO `refresh_token`', [
            'refresh_token' => $refreshToken,
            'client_id' => $clientId,
            'user_id' => $userId,
            'expire' => $expire
        ]);
    }
} 