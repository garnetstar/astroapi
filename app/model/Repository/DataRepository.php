<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 5.7.16
 * Time: 7:51
 */

namespace App\Model\Repository;


class DataRepository extends AbstractRepository
{

    public function addMessier($messId, $ngc, $constellation, $type, $raDeg, $raMin, $decDeg, $decMin, $magnitude, $distance)
    {
        $this->database->query('INSERT INTO `data_messier`', [
            'messier_id' => $messId,
            'ngc' => $ngc,
            'constellation' => $constellation,
            'type' => $type,
            'ra_deg' => $raDeg,
            'ra_min' => $raMin,
            'dec_deg' => $decDeg,
            'dec_min' => $decMin,
            'magnitude' => $magnitude,
            'distance' => $distance,
        ]);
    }

    public function getMessier($offset, $limit)
    {
        $data = $this->database->query('SELECT SQL_CALC_FOUND_ROWS * FROM `data_messier` LIMIT ?,?', $offset, $limit);

        $count = $this->database->query("SELECT FOUND_ROWS() AS `found_rows` LIMIT 1")->fetch();
        return ['data' => $data->fetchAll(), 'count' => $count['found_rows']];
    }
}