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
    public function add($userId, $guid, $from, $to,  $latitude, $longitude, $weather, $log, $notice, $deleted)
    {
        $this->database->query('INSERT INTO `diary`', [
            'guid' => $guid,
            'from' => $from,
            'to' => $to,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'weather' => $weather,
            'log' => $log,
            'notice' => $notice,
            'user_id'=> $userId,
            'deleted' => $deleted
        ]);
    }

    public function update($userId, $guid, $from, $to, $latitude, $longitude, $weather, $log, $notice, $deleted)
    {
        $this->database->query('UPDATE `diary` SET ? WHERE `guid`=?', [
            'from' => $from,
            'to' => $to,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'weather' => $weather,
            'log' => $log,
            'notice' => $notice,
            'user_id'=>$userId,
            'deleted'=> $deleted
        ], $guid);
    }

    public function getOne($guid)
    {
        $data = $this->database->query('SELECT * FROM `diary` WHERE `guid`=?', $guid);
        return $data->fetch();
    }

    public function getOneByDiaryId($diaryId) {
        $data = $this->database->query('SELECT * FROM `diary` WHERE  `diary_id`=?', $diaryId);
        return $data->fetch();
    }

    /**
     * @return int
     */
    public function getNextAutoincrement() {
        return $this->database->query('select max(diary_id) as last_diary_id from diary')->fetch()['last_diary_id'];
    }

    /**
     * @param $userId
     * @return array|\Nette\Database\IRow[]
     */
    public function getActualItems($userId) {
       return $this->database->query('SELECT * FROM `diary` WHERE user_id=? AND deleted=0', $userId)->fetchAll();
    }

    /**
     * @param $diary_id
     * @return \Nette\Database\ResultSet
     */
    public function softDelete($diary_id) {
       return $this->database->query('UPDATE `diary` SET `deleted`=1 WHERE diary_id=?', $diary_id);
    }
}