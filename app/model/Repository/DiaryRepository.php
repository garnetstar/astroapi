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

    public function getData($counter, $user_id)
    {
        $data = $this->database->query('
        SELECT * FROM `diary`
        WHERE `counter` > ? AND `user_id`=?', $counter, $user_id);
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

    /**
     * @return int
     */
    public function getNextAutoincrement() {
        return $this->database->query('select max(diary_id)+1 as last_diary_id from diary')->fetch()['last_diary_id'];
    }
}