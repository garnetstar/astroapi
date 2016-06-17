<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 14.6.16
 * Time: 6:25
 */

namespace App\Presenters;


use Drahak\Restful\Application\UI\ResourcePresenter;
use Drahak\Restful\IResource;

class SamplePresenter extends ResourcePresenter {

    protected $typeMap = array(
        'json' => IResource::JSON,
        'xml' => IResource::XML
    );

    /**
     * @GET sample[.<type xml|json>]
     */
    public function actionContent($type = 'json')
    {
        $this->resource->title = 'REST API';
        $this->resource->subtitle = '';
        $this->sendResource($this->typeMap[$type]);
    }

    /**
     * @DELETE sample[.<type xml|json>]
     */
    public function actionDelete($type = 'json')
    {
        $this->resource->title = 'delete';
        $this->resource->subtitle = '';
        $this->sendResource($this->typeMap[$type]);
    }

    /**
     * @GET sample/detail
     */
    public function actionDetail()
    {
        $this->resource->message = 'Hello world';
    }


} 