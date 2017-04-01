<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 18.12.16
 * Time: 15:36
 */

namespace App\Presenters;


use App\lib\KeyGenerator\KeyGeneratorInterface;
use App\lib\UUID;
use App\Model\Repository\DiaryRepository;
use App\Model\Repository\SettingsRepository;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\DateTime;
use Tracy\Debugger;

class DiaryUiPresenter extends SecureUIPresenter
{
    /** @var  DiaryRepository */
    private $diaryRepository;

    /** @var  SettingsRepository */
    private $settingsRepository;

/** @var  KeyGeneratorInterface */
    private $keyGenerator;

    private $diaryId;

    public function injectDiaryRepository(DiaryRepository $diaryRepository)
    {
        $this->diaryRepository = $diaryRepository;
    }

    public function injectSettingsRepository(SettingsRepository $settingsRepository) {
        $this->settingsRepository = $settingsRepository;
    }
    public function injectKeyGenerator(KeyGeneratorInterface $keyGenerator) {
        $this->keyGenerator = $keyGenerator;
    }

    public function actionAdd()
    {

    }

    public function renderAdd()
    {

        $d = new DateTime();
        $this->template->actualTime = $d->format("Y-m-d") . "T" . $d->format("H:i");
    }

    public function createComponentAddForm()
    {
        $form = new Form();
        $form->addText("from");
        $form->addText("to");
        $form->addText("latitude");
        $form->addText("longitude");
        $form->addTextArea("weather");
        $form->addTextArea("log");
        $form->addText("notice");
        $form->addSubmit("add", "Uložit");
        $form->addHidden("userId");

        $form->onSuccess[] = [$this, "onSuccess"];

        $form->setValues(["userId" => $this->user->getId()]);

        return $form;
    }

    public function onSuccess(Form $form)
    {
        $val = $form->getValues();
        $from = str_replace("T", " ", $val->from);
        $to = str_replace("T", " ", $val->to);
        $userId = $val->userId;

        // vytvořit unikáktní id
        $guid= UUID::v4();

        $this->settingsRepository->increaseCounter();
        $this->diaryRepository->add($userId, $guid, $from, $to, $val->latitude, $val->longitude, $val->weather, $val->log, $val->notice, 0);

        $this->redirect("Homepage:default");
    }

    public function actionEdit($diary_id)
    {
        $this->diaryId = $diary_id;
        $item = $this->diaryRepository->getOneByDiaryId($this->diaryId);
        $this->template->from = str_replace(" ", "T", $item->from);
        $this->template->to = str_replace(" ", "T", $item->to);
        $this->template->latitude = $item->latitude;
        $this->template->longitude = $item->longitude;
        $this->template->log = $item->log;
        $this->template->weather = $item->weather;
        $this->template->notice = $item->notice;
        $this->template->guid=$item->guid;

    }


    public function createComponentEditForm()
    {
        $form = new Form();
        $form->addText("from");
        $form->addText("to");
        $form->addText("latitude");
        $form->addText("longitude");
        $form->addTextArea("weather");
        $form->addTextArea("log");
        $form->addText("notice");
        $form->addHidden("guid");
        $form->addSubmit("add", "Uložit");

        $form->onSuccess[] = [$this, "onSuccessEdit"];

        return $form;
    }

    public function onSuccessEdit($form)
    {
        $val = $form->getValues();
        $from = str_replace("T", " ", $val->from);
        $to = str_replace("T", " ", $val->to);
        $userId = $this->user->getId();

        $this->settingsRepository->increaseCounter();
        $this->diaryRepository->update($userId, $val->guid, $from, $to, $val->latitude, $val->longitude, $val->weather, $val->log, $val->notice, 0);

        $this->redirect("Homepage:default");
    }

}