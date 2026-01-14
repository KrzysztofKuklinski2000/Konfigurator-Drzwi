<?php

namespace App;


class HomeController
{
    public function __construct(private View $view){}
    public function greet()
    {
        $this->view->render(
            ['name' => 'Hello World']
        );
    }
}