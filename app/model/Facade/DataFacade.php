<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 5.7.16
 * Time: 7:59
 */

namespace App\Model\Facade;


use App\Model\Repository\DataRepository;

class DataFacade {

    /** @var  DataRepository */
    private $dataRepository;

    public function __construct(DataRepository $dataRepository){
        $this->dataRepository = $dataRepository;
    }
}