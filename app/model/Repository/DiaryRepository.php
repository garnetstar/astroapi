<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 15.7.16
 * Time: 9:56
 */

namespace App\Model\Repository;


class DiaryRepository extends AbstractRepository
{

    public function getData($counter)
    {
        $data = $this->database->query('SELECT * FROM `diary` WHERE `counter` > ?', $counter);
        return $data->fetchAll();
    }
}