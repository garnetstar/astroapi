<?php

namespace App\Presenters;

use App\Model\Facade\OAuthFacade;
use Drahak\Restful\Application\BadRequestException;
use Drahak\Restful\Application\UI\ResourcePresenter;
use Nette;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends ResourcePresenter
{
    /** @var  int */
    protected $userId;
    /** @var  int */
    protected $clientId;

    /** @var  OAuthFacade */
    protected $oAuthFacade;

    public function injectOAuthFacade(OAuthFacade $OAuthFacade)
    {
        $this->oAuthFacade = $OAuthFacade;
    }

    public function authorize()
    {
//        if (!isset($this->input->access_token) || !$user = $this->oAuthRepository->authorize($this->input->access_token)) {
//            throw BadRequestException::unauthorized('unauthorized');
//        }

//        $this->userId = $user['user_id'];
//        $this->clientId = $user['client_id'];
    }

}
