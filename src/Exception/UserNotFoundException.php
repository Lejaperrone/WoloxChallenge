<?php


namespace App\Exception;


class UserNotFoundException extends WoloxChallengeException
{
    public function __construct($id) {
        parent::__construct('User with id '. $id . ' not found');
    }

    public function __toString() {
        parent::__toString();
    }
}