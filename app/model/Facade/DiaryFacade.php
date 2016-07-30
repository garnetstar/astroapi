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
use Drahak\Restful\Application\BadRequestException;
use Nette\Database\UniqueConstraintViolationException;

class DiaryFacade
{
    /** @var  DiaryRepository */
    private $diaryRepository;

    /** @var  SettingsRepository */
    private $settingsRepository;

    public function __construct(DiaryRepository $diaryRepository, SettingsRepository $settingsRepository)
    {
        $this->diaryRepository = $diaryRepository;
        $this->settingsRepository = $settingsRepository;
    }

    public function syncTo($counter)
    {
//        $this->settingsRepository->increaseCounter();
        $serverCounter = $this->settingsRepository->getCounter();
        return ['objects' => $this->diaryRepository->getData($counter), 'servercounter' => $serverCounter['val']];
    }

    public function syncFrom($objects, $counter)
    {

        $this->settingsRepository->increaseCounter();

        foreach ($objects as $object) {
            try {
                $this->diaryRepository->add($object['guid'], $object['from'], $object['to'], $object['location'], $object['weather'], $object['log'], $object['notice']);
            } catch (UniqueConstraintViolationException $e) {
                $serverCounter = $this->diaryRepository->getOne($object['guid'])['counter'];

                if ($object['counter'] > 0 && $serverCounter <= $counter) {
                    $this->diaryRepository->update($object['guid'], $object['from'], $object['to'], $object['location'], $object['weather'], $object['log'], $object['notice']);
                } else {
                    throw BadRequestException::unprocessableEntity([], 'version conflict');
                }
            }
        }
    }
}