<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 18.6.16
 * Time: 7:52
 */

namespace App\Presenters;


use Drahak\Restful\Application\UI\ResourcePresenter;
use Drahak\Restful\Validation\Error;

class RestErrorPresenter extends ResourcePresenter{

    public function actionDefault($exception)
    {
        $this->sendErrorResource($exception);
    }

} 