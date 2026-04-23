<?php

namespace App;

interface AuthInterface
{
    public function signUp(): void;

    public function logIn(): void;

    public function logOut(): void;
}
