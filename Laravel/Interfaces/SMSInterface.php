<?php

namespace App\Interfaces;

interface SMSInterface
{
    public function send(string $to, string $message) :array;
    public function balance() :array;
}