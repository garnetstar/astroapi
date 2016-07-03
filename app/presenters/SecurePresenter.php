<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 3.7.16
 * Time: 8:31
 */

namespace App\Presenters;


use App\Model\Facade\Error\NotFoundException;
use Drahak\Restful\Application\BadRequestException;

class SecurePresenter extends BasePresenter
{

    public function startup()
    {
        parent::startup();

        if (!isset($this->input->access_token)) {
            throw BadRequestException::unauthorized('unauthorized');
        }

        try {

            $user = $this->oAuthFacade->authorize($this->input->access_token);

        } catch (NotFoundException $e) {
            throw BadRequestException::unauthorized('unauthorized');
        }

    }
}
