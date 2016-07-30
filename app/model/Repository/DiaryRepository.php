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

    /**
     * @param $guid
     * @param $from
     * @param $to
     * @param $location
     * @param $weather
     * @param $log
     * @param $notice
     */
    public function add($guid, $from, $to, $location, $weather, $log, $notice)
    {
        $this->database->query('INSERT INTO `diary`', [
            'guid' => $guid,
            'from' => $from,
            'to' => $to,
            'location' => $location,
            'weather' => $weather,
            'log' => $log,
            'notice' => $notice
        ]);
    }

    public function update($guid, $from, $to, $location, $weather, $log, $notice)
    {
        $this->database->query('UPDATE `diary` SET ? WHERE `guid`=?', [
            'guid' => $guid,
            'from' => $from,
            'to' => $to,
            'location' => $location,
            'weather' => $weather,
            'log' => $log,
            'notice' => $notice
        ], $guid);
    }

    public function getOne($guid)
    {
        $data = $this->database->query('SELECT * FROM `diary` WHERE `guid`=?', $guid);
        return $data->fetch();
    }
}