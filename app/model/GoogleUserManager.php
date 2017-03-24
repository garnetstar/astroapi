<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 5.3.17
 * Time: 15:13
 */

namespace App\Model;


use App\Model\Repository\UserRepository;
use Nette\Database\Context;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\IIdentity;

class GoogleUserManager implements IAuthenticator
{

    /** @var  UserRepository */
   private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository= $userRepository;
    }

    /**
     * Performs an authentication against e.g. database.
     * and returns IIdentity on success or throws AuthenticationException
     * @return IIdentity
     * @throws AuthenticationException
     */
    function authenticate(array $credentials)
    {
        $idToken = $credentials[0]['id_token'];


        if (empty($idToken)) {
            throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

        }

        $client = new \Google_Client();

        $client->setAuthConfigFile('client_secrets.json');

        $payload = $client->verifyIdToken($idToken);

        $data = array(
            "first_name" => $payload["given_name"],
            "surname" => $payload["family_name"],
            "picture" => $payload["picture"],
            "login" => $payload['email']
        );

        if(!$userId = $this->userRepository->getUserIdByLogin($payload['email'], UserRepository::CLIENT_GOOGLE)) {
            /* @var \Nette\Database\ResultSet */
            $userId = $this->userRepository->addUser($payload["given_name"],$payload["family_name"], $payload['email'],"",UserRepository::CLIENT_GOOGLE);
        }

        return new Identity($userId, $payload['email'], $data);

    }
}