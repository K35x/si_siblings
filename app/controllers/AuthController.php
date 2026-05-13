<?php

class AuthController extends Controller
{
    public function login(): void
    {
        $this->view('layouts.auth');
    }
}
