<?php

namespace SandboxBundle\Service;

use SandboxBundle\Domain\User;

abstract class UserRepository
{
    abstract public function add(User $user);

    abstract public function findByUserId($userId);
}