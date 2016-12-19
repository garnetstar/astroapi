<?php

namespace App\Presenters;

use Nette;
use App\Forms\SignFormFactory;


class SignPresenter extends Nette\Application\UI\Presenter
{
    /** @var SignFormFactory @inject */
    public $factory;

    public function actionIn()
    {
    }

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
//	protected function createComponentSignInForm()
//	{
//		$form = $this->factory->create();
//		$form->onSuccess[] = function ($form) {
//			$form->getPresenter()->redirect('Homepage:');
//		};
//		return $form;
//	}


    public function createComponentSignInForm()
    {
        $form = new Nette\Application\UI\Form();
        $form->addText("username", "Username");
        $form->addText("password", "Password");
        $form->addSubmit("send", "Login");
        $form->onSuccess[] = array($this, 'signInFormSucceeded');
        return $form;
    }

    public function signInFormSucceeded($form)
    {
        try {

            $values = $form->getValues();

            $this->getUser()->login($values->username, $values->password);
            $this->redirect("Homepage:");
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
            return;
        }

        $this->restoreRequest($this->backlink);
    }

    public function actionLogout()
    {
        $this->user->logout(true);
        $this->redirect("Homepage:");
    }

    public function actionOut()
    {
        $this->getUser()->logout();
    }

}
