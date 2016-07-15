<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 15.7.16
 * Time: 9:49
 */

namespace App\Model\Facade;


use App\Model\Repository\DiaryRepository;

class DiaryFacade
{
    /** @var  DiaryRepository */
    private $diaryRepository;

    public function __construct(DiaryRepository $diaryRepository) {
        $this->diaryRepository = $diaryRepository;
    }

    public function sync($counter)
    {
        return $this->diaryRepository->getData($counter);
    }

} 