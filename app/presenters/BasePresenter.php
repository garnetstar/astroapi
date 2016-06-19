<?php

namespace App\Presenters;

use Drahak\Restful\Application\BadRequestException;
use Drahak\Restful\Application\UI\ResourcePresenter;
use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends ResourcePresenter
{
    /** @var  int */
    protected $userId;
    /** @var  int */
    protected $clientId;

    /** @var  Model\OAuthRepository */
    private $oAuthRepository;

    public function injectOAuthRepository(Model\OAuthRepository $OAuthRepository)
    {
        $this->oAuthRepository = $OAuthRepository;
    }

    public function authorize()
    {
        if (!isset($this->input->access_token) || !$user = $this->oAuthRepository->authorize($this->input->access_token)) {
            throw BadRequestException::unauthorized('unauthorized');
        }

        $this->userId = $user['user_id'];
        $this->clientId = $user['client_id'];
    }

}
