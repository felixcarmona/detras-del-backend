<?php

namespace SandboxBundle\Service;

use Doctrine\DBAL\Driver\PDOConnection;
use Metrics\Metrics;
use Metrics\MetricsProvider;
use SandboxBundle\Domain\User;
use SandboxBundle\Exception\UserRepositoryException;

class PdoUserRepository extends UserRepository implements MetricsProvider
{
    private $pdo;

    private $metrics;

    public function __construct(PdoConnection $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(User $user)
    {
        $this->getMetrics()->startTimer('user_add.execution_time');

        $userId = $user->getId();

        if ($this->findByUserId($userId)) {
            throw new UserRepositoryException(sprintf('There is an existent user with the %s id', $userId));
        }

        $statement = $this->pdo->prepare('INSERT INTO users (user_id, name) VALUES (:user_id, :name)');
        $statement->execute(array(':user_id' => $userId, ':name' => $user->getName()));

        $this->getMetrics()->stopTimer('user_add.execution_time');
    }

    public function findByUserId($userId)
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE user_id = :user_id');
        $statement->execute(array(':user_id' => $userId));
        $result = $statement->fetch();

        return $result;
    }

    public function getMetrics()
    {
        if (!isset($this->metrics)) {
            $this->metrics = new Metrics();
        }

        return $this->metrics;
    }
}