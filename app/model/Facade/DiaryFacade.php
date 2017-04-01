<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 15.7.16
 * Time: 9:49
 */

namespace App\Model\Facade;


use App\Model\Repository\DiaryRepository;
use App\Model\Repository\SettingsRepository;
use App\Model\Repository\UserRepository;
use Drahak\Restful\Application\BadRequestException;
use Nette\Database\Context;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\DateTime;
use Tracy\Debugger;

class DiaryFacade
{
    /** @var  DiaryRepository */
    private $diaryRepository;

    /** @var  SettingsRepository */
    private $settingsRepository;

    /** @var  UserRepository */

    private $userRepository;

    /** @var  Context */
    private $database;

    private $counterIncreased = false;

    public function __construct(Context $database, DiaryRepository $diaryRepository, SettingsRepository $settingsRepository, UserRepository $userRepository)
    {
        $this->diaryRepository = $diaryRepository;
        $this->settingsRepository = $settingsRepository;
        $this->userRepository = $userRepository;
        $this->database = $database;
    }

    public function syncTo($counter, $user_id)
    {
        $serverCounter = $this->settingsRepository->getCounter();
        $objects = array();
        $objectsRaw = $this->diaryRepository->getData($counter, $user_id);
        foreach ($objectsRaw as $one) {

            $oneObject = [
                "guid" => $one->guid,
                "from" => $one->from->format("Y-m-d H:i:s"),
                "to" => $one->to->format("Y-m-d H:i:s"),
                "counter" => $one->counter,
                "latitude" => (float)$one->latitude,
                "longitude" => (float)$one->longitude,
                "weather" => $one->weather,
                "log" => $one->log,
                "timestamp" => $one->timestamp->format("Y-m-d H:i:s"),
                "deleted" => $one->deleted
            ];
            $objects[] = $oneObject;
        }

        return [
            'objects' => $objects,
            'servercounter' => $serverCounter,
            'user_id' => $user_id,
        ];


    }

    public function syncFrom($objects, $userId)
    {
        $this->database->beginTransaction();
        try {
            foreach ($objects as $object) {
                // nové objekty, jejich GUID by nemělo být v databázi
                if ((int)$object['new'] == 1) {
                    $this->increaseCounter();
                    $this->diaryRepository->add($userId, $object['guid'], $object['from'], $object['to'], $object['latitude'], $object['longitude'], $object['weather'], $object['log'], $object['deleted']);
                    $modify = true;
                } else {

                    $serverRowCounter = $this->diaryRepository->getOne($object['guid'])['counter'];

                    if ($serverRowCounter <= $object['row_counter']) {
                        $this->increaseCounter();
                        $this->diaryRepository->update($userId, $object['guid'], $object['from'], $object['to'], $object['latitude'], $object['longitude'], $object['weather'], $object['log'], $object['deleted']);
                        $modify = true;
                    } else {
                        throw BadRequestException::unprocessableEntity([], 'version conflict 1.' . $serverRowCounter . ' 2.' . $object['row_counter']);
                    }
                }
            }
        } catch
        (UniqueConstraintViolationException $e) {
            //U nových objektů je konflikt s jejich GUID
            throw BadRequestException::unprocessableEntity([], 'unique id conflict');
            $this->database->rollBack();
        }

        $this->database->commit();
    }

    private function increaseCounter() {
        if(! $this->counterIncreased) {
            //pokud došlo k úpravě dat, je třeba navýšit verzi serverCounteru
            $this->settingsRepository->increaseCounter();
            $this->counterIncreased = true;
        }
    }
}