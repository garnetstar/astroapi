<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 18.7.16
 * Time: 6:32
 */

namespace App\Model\Repository;


class SettingsRepository extends AbstractRepository{

    public function getCounter() {
        $data = $this->database->query('SELECT `val` FROM `settings` WHERE `key`=?', 'counter');
        return $data->fetch()['val'];
    }

    public function increaseCounter() {
        $this->database->query('UPDATE `settings` SET `val` = `val`+1 WHERE `key`=?', 'counter' );
    }

} 