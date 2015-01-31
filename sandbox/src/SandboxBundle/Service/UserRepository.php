<?php

namespace SandboxBundle\Service;

use SandboxBundle\Domain\User;

interface UserRepository
{
    public function add(User $user);
    public function findByUserId($userId);
}
