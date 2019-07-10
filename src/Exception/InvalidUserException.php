<?php


namespace App\Exception;


class InvalidUserException extends WoloxChallengeException
{
    public function __construct($listOfErrors)
    {

        $message = '';
        foreach ($listOfErrors as $error) {
            $message .= $error . ' ';
        }

        parent::__construct($message);
    }

    public function __toString()
    {
        parent::__toString();
    }
}