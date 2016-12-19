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

    public function __construct(Context $database, DiaryRepository $diaryRepository, SettingsRepository $settingsRepository, UserRepository $userRepository)
    {
        $this->diaryRepository = $diaryRepository;
        $this->settingsRepository = $settingsRepository;
        $this->userRepository = $userRepository;
        $this->database = $database;
    }

    public function syncTo($counter, $user_id)
    {
//        $this->settingsRepository->increaseCounter();
        $serverCounter = $this->settingsRepository->getCounter() - 1;
        $lastAutoincrement = $this->diaryRepository->getNextAutoincrement();

//        print_r($this->diaryRepository->getData($counter, $user_id));
        $objectsRaw = $this->diaryRepository->getData($counter, $user_id);
        foreach ($objectsRaw as $one) {

            $oneObject = [
                "guid" => $one->guid,
                "from" => $one->from->format("Y-m-d H:i:s"),
                "to" => $one->to->format("Y-m-d H:i:s"),
                "counter" => $one->counter,
                "deleted" => $one->deleted
            ];
            $objects[] = $oneObject;
        }

        return [
            'objects' => $objects,
            'servercounter' => $serverCounter,
            'next_id' => $lastAutoincrement,
            'user_id' => $user_id,
        ];
    }

    public function syncFrom($objects, $authToken, $counter)
    {

        $updated = false;

        $userId = $this->userRepository->getUserIdByAccessToken($authToken);

        $this->database->beginTransaction();

        foreach ($objects as $object) {

            $clientRowCounter = $object['row_counter'];
            try {
                $this->diaryRepository->add($userId, $object['guid'], $object['from'], $object['to'], $object['latitude'], $object['longitude'], $object['weather'], $object['log'], $object['notice'], $object['deleted']);
                $updated = true;
            } catch (UniqueConstraintViolationException $e) {
                // guid uz v databazi je
                $serverRowCounter = $this->diaryRepository->getOne($object['guid'])['counter'];

                if ($serverRowCounter <= $counter) {
                    $this->diaryRepository->update($userId, $object['guid'], $object['from'], $object['to'], $object['latitude'], $object['longitude'], $object['weather'], $object['log'], $object['notice'], $object['deleted']);
                    $updated = true;
                } else {
                    $this->database->rollBack();
                    throw BadRequestException::unprocessableEntity([], 'version conflict ');
                }
            }
        }

        if ($updated) {
            $this->settingsRepository->increaseCounter();
        }

        $this->database->commit();
    }
}