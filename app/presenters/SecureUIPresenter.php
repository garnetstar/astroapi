<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 18.12.16
 * Time: 22:22
 */

namespace App\Presenters;


use Nette\Application\UI\Presenter;

class SecureUIPresenter extends Presenter {

    public function startup() {

        parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }

} 