<?php

namespace App;


class Controller
{
    public function greet($name)
    {
        return "Hello, " . htmlspecialchars($name) . "!";
    }
}