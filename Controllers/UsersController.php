<?php

namespace Controllers;

class UsersController
{
    public function register()
    {
        echo "this is register method";
    }

    public function login()
    {
        echo "this is login method";
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