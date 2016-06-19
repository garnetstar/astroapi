<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 19.6.16
 * Time: 15:30
 */

namespace App\lib\KeyGenerator;


class OpenSSLGenerator implements KeyGeneratorInterface{

    /** @var  String */
    public $algorithm;

    public function __construct($algorithm) {
        $this->algorithm = $algorithm;
    }

    public function getKey()
    {
        $bytes = openssl_random_pseudo_bytes(40);
        return hash($this->algorithm, $bytes);
    }
}