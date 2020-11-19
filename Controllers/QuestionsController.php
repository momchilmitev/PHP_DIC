<?php

namespace Controllers;

class QuestionsController
{
    public function ask()
    {
        echo "you are asking a question";
    }

    public function answer($id)
    {
        echo "you are answering on $id question";
    }
}