<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 15.7.16
 * Time: 9:37
 */

namespace App\Presenters;


use App\Model\Facade\DiaryFacade;

class DiaryPresenter extends SecurePresenter
{

    /** @var  DiaryFacade */
    private $diaryFacade;

    public function injectDiaryFacade(DiaryFacade $diaryFacade)
    {
        $this->diaryFacade = $diaryFacade;
    }

    public function actionList($id)
    {
        $this->resource = $this->diaryFacade->sync($id);
    }
} 