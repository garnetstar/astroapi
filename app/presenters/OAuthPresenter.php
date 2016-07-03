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

class OAuthPresenter extends BasePresenter
{
    public function actionToken()
    {
        if (!isset($this->input->login) || !isset($this->input->password) || !isset($this->input->client_id)) {
            throw new \Exception('Reques does not contain proper field', 400);
        };

        $duration = 3600;

        try {
            $token = $this->oAuthFacade->getToken($this->input->login, $this->input->password, $this->input->client_id);
        } catch (NotFoundException $e) {
            throw BadRequestException::unauthorized('unauthorized');
        }

        $this->resource->access_token = $token['accessToken'];
        $this->resource->token_type = "bearer";
        $this->resource->expires_in = $duration;
        $this->resource->refresh_token = $token['refreshToken'];

    }

    private function getRefreshToken($userId)
    {

    }
}