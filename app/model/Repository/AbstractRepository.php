<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2.7.16
 * Time: 21:41
 */

namespace App\Model\Repository;

use Nette\Database\Context;

abstract class AbstractRepository
{

    /** @var  Context */
    protected $database;

   public function __construct(Context $database) {
       $this->database = $database;
   }
} 