<?php


namespace App\Exception;


class UserAlreadyExistException extends WoloxChallengeException
{
    public function __construct($email)
    {
        parent::__construct('User with email ' . $email . ' already exists');
    }

    public function __toString()
    {
        parent::__toString();
    }
}