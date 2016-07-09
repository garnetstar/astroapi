<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2.7.16
 * Time: 20:52
 */

namespace App\Model\Facade;


use App\lib\ExpirationFactory;
use App\lib\KeyGenerator\KeyGeneratorInterface;
use App\Model\Facade\Error\NotFoundException;
use App\Model\Repository\AccessTokenRepository;
use App\Model\Repository\RefreshTokenRepository;
use App\Model\Repository\UserRepository;

class OAuthFacade
{
    /** @var  KeyGeneratorInterface */
    private $keyGenerator;

    /** @var  UserRepository */
    private $userRepository;

    /** @var  ExpirationFactory */
    private $expirationFactory;

    /** @var  AccessTokenRepository */
    private $accessTokenRepository;

    /** @var  RefreshTokenRepository */
    private $refreshTokenRepository;

    /** @var  int */
    private $accessTokenDuration;
    /** @var  int */
    private $refreshTokenDuration;

    public function __construct($accessTokenDuration, $refreshTokenDuration, KeyGeneratorInterface $keyGenerator, UserRepository $userRepository, AccessTokenRepository $accessTokenRepository, RefreshTokenRepository $refreshTokenRepository, ExpirationFactory $expirationFactory)
    {
        $this->keyGenerator = $keyGenerator;
        $this->userRepository = $userRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->expirationFactory = $expirationFactory;
        $this->accessTokenDuration = $accessTokenDuration;
        $this->refreshTokenDuration = $refreshTokenDuration;
    }

    /**
     * @param $accessToken
     * @return bool|\Nette\Database\IRow|\Nette\Database\Row
     * @throws Error\NotFoundException
     */
    public function authorize($accessToken)
    {
        if (! $token = $this->accessTokenRepository->getAccessToken($accessToken)) {
            throw new NotFoundException('access_token not found');
        }

        return $token;
    }

    /**
     * @param $login
     * @param $password
     * @param $clientId
     * @param $duration
     * @return array
     * @throws Error\NotFoundException
     */
    public function getToken($login, $password, $clientId)
    {
        if (!$user = $this->userRepository->getUser($login, $password, $clientId)) {
            throw new NotFoundException('User does not exists');
        }

        $newAccessToken = $this->keyGenerator->getKey();
        $expiration = $this->expirationFactory->getExpiredTime($this->accessTokenDuration);

        $this->accessTokenRepository->addAccessToken($newAccessToken, $user->user_id, $user->client_id, $expiration);

        if (!$refreshToken = $this->refreshTokenRepository->getRefreshToken($user->user_id, $user->client_id)) {

            $refreshToken = $this->keyGenerator->getKey();
            // 30 dni
            $expiration = $this->expirationFactory->getExpiredTime($this->refreshTokenDuration);

            $this->refreshTokenRepository->addRefreshToken($refreshToken, $user->user_id, $user->client_id, $expiration);
        } else {
            $refreshToken = $refreshToken->refresh_token;
        }

        return ['accessToken' => $newAccessToken, 'refreshToken' => $refreshToken];
    }

    public function getRefreshToken($userId, $clientId, $refreshToken)
    {

    }
}