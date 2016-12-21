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
use App\Model\Repository\UserRepository;
use Drahak\Restful\Application\BadRequestException;

class DiaryPresenter extends SecurePresenter
{

    /** @var  DiaryFacade */
    private $diaryFacade;

    /** @var  SettingsRepository */
    private $settingsRepository;

    /** @var  UserRepository */
    private $userRepository;

    public function injectDiaryFacade(DiaryFacade $diaryFacade, SettingsRepository $settingsRepository, UserRepository $userRepository)
    {
        $this->diaryFacade = $diaryFacade;
        $this->settingsRepository = $settingsRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @GET diary[.<type xml|json>]
     */
    public function actionList($id)
    {
        $user_id = $this->userRepository->getUserIdByAccessToken($this->input->access_token);
        $this->resource = $this->diaryFacade->syncTo($id, $user_id);
    }

    /*
    { "objects": [
    {
      "guid": "39-1",
      "from": "2016-12-20 13:52:00",
      "to": "2016-12-21 13:52:00",
      "row_counter": 4,
      "latitude": "",
      "longitude": "",
      "weather": "polojasno místy trakaře",
      "log": "",
      "timestamp": "2016-12-20 13:53:06",
      "deleted": 0,
      "new": 0
         }
        ]
    }
     */
    public function actionUpdate()
    {
        if (!isset($this->input->objects)) {
            throw BadRequestException::unprocessableEntity([], 'Wrong data format');
        }

        $this->diaryFacade->syncFrom($this->input->objects, $this->authToken);
        $this->resource->status = 'ok';
    }

}