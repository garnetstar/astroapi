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
use App\Presenters\SecurePresenter;
use Nette\FileNotFoundException;
use Nette\Security\Passwords;

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

    /** @var  string */
    private $clientId;

    public function __construct($accessTokenDuration, $refreshTokenDuration, $clientId, KeyGeneratorInterface $keyGenerator, UserRepository $userRepository, AccessTokenRepository $accessTokenRepository, RefreshTokenRepository $refreshTokenRepository, ExpirationFactory $expirationFactory)
    {
        $this->keyGenerator = $keyGenerator;
        $this->userRepository = $userRepository;
        $this->clientId = $clientId;
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
        //autorizace pomocí Google id_token
        try {
            $client = new \Google_Client(['client_id' => $this->clientId]);
            if ($data = $client->verifyIdToken($accessToken)) {
                if (!$userId = $this->userRepository->getUserIdByLogin($data["email"], 2)) {
                    $userId = $this->userRepository->addUser($data["given_name"], $data["family_name"], $data['email'], "", UserRepository::CLIENT_GOOGLE);
                }
                return [
                    "user_id" => $userId,
                    "client_id" => 2
                ];

            } else {
                throw new NotFoundException('expired');
            }
        } catch (\UnexpectedValueException $e) {

            if (!$token = $this->accessTokenRepository->getAccessToken($accessToken)) {
                throw new NotFoundException('access_token not found');
            }
        }
        return (array)$token;
    }

    /**
     * @param $login
     * @param $password
     * @param $clientId
     * @param $duration
     * @return array
     * @throws Error\NotFoundException
     */
    public function getToken($login, $clientId)
    {
        if (!$user = $this->userRepository->getUser($login, $clientId)) {
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

    public function getTokenByRefreshToken($clientId, $refreshToken)
    {
        if (!$token = $this->refreshTokenRepository->getRefreshToken($clientId, $refreshToken)) {
            throw new NotFoundException("Refresh token does not exists");
        }

        $newAccessToken = $this->keyGenerator->getKey();
        $expiration = $this->expirationFactory->getExpiredTime($this->accessTokenDuration);

        $this->accessTokenRepository->addAccessToken($newAccessToken, $token->user_id, $token->client_id, $expiration);

        return $newAccessToken;
    }

}