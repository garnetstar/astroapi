<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Tracy\Debugger;


class HomepagePresenter extends SecureUIPresenter
{

    /** @var  Model\Repository\DataRepository */
    private $dataRepository;

    /** @var  Model\Repository\DiaryRepository */
    private $diaryRepository;

    public function injectDataRepository(Model\Repository\DataRepository $dataRepository)
    {
        $this->dataRepository = $dataRepository;
    }

    public function injectDiaryRepository(Model\Repository\DiaryRepository $diaryRepository)
    {
        $this->diaryRepository = $diaryRepository;
    }

    public function actionDefault()
    {
        $items = $this->diaryRepository->getActualItems($this->user->getId());

        $this->template->items = $items;
    }

    public function renderDefault()
    {
        $this->template->anyVariable = 'any value';
    }

    public function handleDelete($diary_id)
    {
        $this->diaryRepository->softDelete($diary_id);
        $this->redirect('this');
    }

}
