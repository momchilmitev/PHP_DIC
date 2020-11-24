<?php

namespace Controllers;

use DTO\ViewModels\UserLoginViewModel;
use ViewEngine\ViewInterface;

class UsersController
{
    public function register()
    {
        echo "this is register method";
    }

    public function login(int $id, string $name, ViewInterface $view)
    {
        $viewModel = new UserLoginViewModel($id, $name);
        $view->render($viewModel);
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