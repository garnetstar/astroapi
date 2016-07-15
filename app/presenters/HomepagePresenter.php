<?php

namespace App\Presenters;

use Nette;
use App\Model;


class HomepagePresenter extends Nette\Application\UI\Presenter
{

    /** @var  Model\Repository\DataRepository */
    private $dataRepository;

    public function injectDataRepository(Model\Repository\DataRepository $dataRepository)
    {
        $this->dataRepository = $dataRepository;
    }

    public function actionDefault()
    {

        $file = "C:\wamp\www\messier.txt";

        $data = file($file);

        foreach ($data as $one) {
            $data = explode(" ", $one);
            $this->dataRepository->addMessier(intval($data[0]), $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9]);
        }


        die("konec testovani");
    }

    public function renderDefault()
    {
        $this->template->anyVariable = 'any value';
    }

}
