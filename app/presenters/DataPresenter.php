<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 6.7.16
 * Time: 7:48
 */

namespace App\Presenters;


use App\Model\Repository\DataRepository;
use Drahak\Restful\Resource\Link;
use Tracy\Debugger;

class DataPresenter extends SecurePresenter
{
    /** @var  DataRepository */
    private $dataRepository;

    public function injectDataRepository(DataRepository $dataRepository)
    {
        $this->dataRepository = $dataRepository;
    }

    public function startup()
    {
        parent::startup();

    }

    public function actionMessierData()
    {
        $this->initPaginator();

        $data = $this->dataRepository->getMessier($this->paginator->getOffset(), $this->paginator->getLength());
        $count = $data['count'];

        $this->paginator->setItemCount($count);

        $this->resource = $data['data'];

        $this->setPaginationToHeader();
    }
}