<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 17.6.16
 * Time: 21:08
 */

namespace App\Presenters;


use App\Model\OAuthRepository;
use Drahak\Restful\Application\BadRequestException;
use Drahak\Restful\Application\UI\ResourcePresenter;
use Nette\Neon\Exception;

class OAuthPresenter extends ResourcePresenter
{
    public function actionToken()
    {
        if (!isset($this->input->login) || !isset($this->input->password) || !isset($this->input->client_id)) {
            throw new \Exception('Reques does not contain proper field', 400);
        };

        if (!$token = $this->oAuthRepository->getToken($this->input->login, $this->input->password, $this->input->client_id)) {
            throw BadRequestException::unauthorized('unauthorized');
        }

        return $this->resource->token = $token;
    }
}