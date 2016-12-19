<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 14.12.16
 * Time: 20:39
 */

namespace App\lib;


use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;

class Authenticator extends Object implements IAuthenticator {

    /**
     * Performs an authentication against e.g. database.
     * and returns IIdentity on success or throws AuthenticationException
     * @return IIdentity
     * @throws AuthenticationException
     */
    function authenticate(array $credentials)
    {
        die("aaaaaaaaaaaa");
    }
}