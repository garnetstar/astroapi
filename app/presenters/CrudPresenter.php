<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 14.6.16
 * Time: 9:02
 */

namespace App\Presenters;


use Drahak\Restful\Application\UI\ResourcePresenter;

class CrudPresenter extends SecurePresenter{

    public function actionCreate()
    {
        $this->resource->action = 'Create';
    }

    public function actionRead()
    {
        $this->authorize();
        $this->resource->action = 'Read';
    }

    public function actionUpdate()
    {
        $this->authorize();
        $this->resource->action = 'Update';


        $this->resource->message = isset($this->input->message) ? $this->input->message : 'no message';

        $this->resource->client = $this->clientId;
        $this->resource->user = $this->userId;


    }

    public function actionDelete()
    {
        $this->resource->action = 'Delete';
    }

} 