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

    /** @var  Model\Repository\UserRepository */
    private $userRepository;

    /** @var  Model\Repository\SettingsRepository */
    private $settingsRepository;

    public function injectSettingsReposiotry(Model\Repository\SettingsRepository $settingsRepository) {
        $this->settingsRepository = $settingsRepository;
    }

    public function injectUserRepository(Model\Repository\UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

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
        $this->template->user = $this->user;
    }

    public function handleDelete($diary_id)
    {
        $this->settingsRepository->increaseCounter();
        $this->diaryRepository->softDelete($diary_id);
        $this->redirect('this');
    }

}
