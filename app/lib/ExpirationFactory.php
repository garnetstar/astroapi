<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2.7.16
 * Time: 22:32
 */

namespace App\lib;


class ExpirationFactory {

    public function getExpiredTime($duration) {
        return $expire = date('Y-m-d H:i:s', time() + $duration);
    }

} 