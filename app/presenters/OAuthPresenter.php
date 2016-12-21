<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 17.6.16
 * Time: 21:08
 */

namespace App\Presenters;


use App\Model\Facade\Error\NotFoundException;
use App\Model\OAuthRepository;
use Drahak\Restful\Application\BadRequestException;
use Nette\Security\AuthenticationException;
use Nette\Utils\Paginator;

class OAuthPresenter extends BasePresenter
{
    private $duration = 3600;

    public function actionToken()
    {
        if (!isset($this->input->grant_type)) {
            throw new \Exception('There is no grant_type parameter', 400);
        }

        switch ($this->input->grant_type) {
            case "password";
                $this->accessToken();
                break;
            case "refresh_token";
                $this->refreshToken();
                break;
            default:
                throw new \Exception('Unsupported grant_type', 400);
        }
    }

    private function accessToken()
    {

        $this->properFieldException(['login', 'password', 'client_id']);


        try {
            $this->user->login($this->input->login, $this->input->password);
            $token = $this->oAuthFacade->getToken($this->input->login, $this->input->client_id);
        }catch (AuthenticationException $e) {
            throw BadRequestException::unauthorized('unauthorized');
        } catch (NotFoundException $e) {
            throw BadRequestException::unauthorized('unauthorized, bad client_id');
        }

        $this->tokenResponse($token['accessToken'], $token['refreshToken']);
    }

    private function refreshToken()
    {
        $this->properFieldException(['refresh_token', 'client_id']);

        try {
            $token = $this->oAuthFacade->getTokenByRefreshToken($this->input->client_id, $this->input->refresh_token);
        } catch (NotFoundException $e) {
            throw BadRequestException::unauthorized('refresh token not found or expired');
        }

        $this->tokenResponse($token, $this->input->refresh_token);
    }

    /**
     * @param array $fields
     * @throws \Exception
     */
    private function properFieldException(array $fields)
    {
        foreach ($fields as $field) {
            if (!isset($this->input->$field)) {
                throw new \Exception('Reques does not contain proper field', 400);
            }
        }
    }

    private function tokenResponse($accessToken, $refreshToken)
    {
        $this->resource->access_token = $accessToken;
        $this->resource->token_type = "bearer";
        $this->resource->expires_in = $this->duration;
        $this->resource->refresh_token = $refreshToken;
    }
}