<?php

namespace App\Services;

class HelloServiceIndonesia implements HelloService
{
    public function hello(string $name): string
    {
        // TODO: Implement hello() method.
        return "Halo $name";
    }
}