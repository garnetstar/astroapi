<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 16.2.17
 * Time: 21:18
 */

namespace App\Presenters;


use App\Model\Repository\UserRepository;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\Passwords;
use Nette\Security\User;

class SignUpPresenter extends Presenter {

    /** @var  UserRepository */
    private $userRepository;

    public function injectUserRepository(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function actionDefault()
    {
    }

    public function createComponentSignUpForm()
    {
        $form = new Form();
        $form->addText("firstName", "Username");
        $form->addText("surname", "Username");
        $form->addText("login", "Username");
        $form->addText("password", "Password");
        $form->addSubmit("send", "Login");
        $form->onSuccess[] = array($this, 'signUpFormSucceeded');
        return $form;
    }

    public function signUpFormSucceeded(Form $form) {
        $val = $form->getValues();

        $this->userRepository->addUser($val->firstName, $val->surname, $val->login, Passwords::hash($val->password), 1);

        $this->getUser()->login($val->login, $val->password);

        $this->redirect("Homepage:");

    }

} 