<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 15.7.16
 * Time: 9:37
 */

namespace App\Presenters;


use App\Model\Facade\DiaryFacade;
use App\Model\Repository\SettingsRepository;
use Drahak\Restful\Application\BadRequestException;

class DiaryPresenter extends SecurePresenter
{

    /** @var  DiaryFacade */
    private $diaryFacade;

    /** @var  SettingsRepository */
    private $settingsRepository;

    public function injectDiaryFacade(DiaryFacade $diaryFacade, SettingsRepository $settingsRepository)
    {
        $this->diaryFacade = $diaryFacade;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @GET diary[.<type xml|json>]
     */
    public function actionList($id)
    {
        $this->resource = $this->diaryFacade->syncTo($id);
    }

    /**
     * @POST diary[.<type xml|json>]
     * "objects": [
     * {
     * "guid": 3,
     * "from": "-001-11-30T00:00:00+01:00",
     * "to": "2016-07-22T00:00:00+02:00",
     * "location": "",
     * "weather": "",
     * "log": "",
     * "notice": "",
     * "counter": 3,
     * "created": "2016-07-18T07:41:03+02:00"
     * },
     * {
     * "guid": 3,
     * "from": "-001-11-30T00:00:00+01:00",
     * "to": "2016-07-22T00:00:00+02:00",
     * "location": "",
     * "weather": "",
     * "log": "",
     * "notice": "",
     * "counter": 3,
     * "created": "2016-07-18T07:41:03+02:00"
     * }
     * ]
     */
    public function actionUpdate($id)
    {

        if (!isset($this->input->objects)) {
            throw BadRequestException::unprocessableEntity([], 'Wrong data format');
        }

        $this->diaryFacade->syncFrom($this->input->objects, $this->input->counter);

        $serverCounter = $this->settingsRepository->getCounter();
        $this->resource->serverCounter = $serverCounter;

        $this->resource->status = 'ok';


    }

}