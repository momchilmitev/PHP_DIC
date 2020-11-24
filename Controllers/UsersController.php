<?php

namespace Controllers;

use DTO\ViewModels\UserLoginViewModel;
use ViewEngine\ViewInterface;

class UsersController
{
    private ViewInterface $view;

    /**
     * UsersController constructor.
     * @param ViewInterface $view
     */
    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
    }


    public function register()
    {
        echo "this is register method";
    }

    public function login(int $id, string $name)
    {
        $viewModel = new UserLoginViewModel($id, $name);
        $this->view->render($viewModel);
    }

    public function profile()
    {
        echo "profile opened";
    }

    public function editProfile($id)
    {
        echo "edit profile $id page opened";
        echo "<form method='post'><input type='submit'></form>";
    }

    public function editProfileProcess($id)
    {
        echo "form for $id profile edit submitted";
    }
}