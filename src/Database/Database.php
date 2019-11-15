<?php

namespace App\Database;

use App\AppException;

class Database
{
    protected $pdo;

    /**
     * Database constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $table
     * @param array $item
     * @throws AppException
     */
    public function insert(string $table, array $item): void
    {
        try
        {
            if(!$this->pdo->inTransaction())
            {
                $this->pdo->beginTransaction();
            }
            $names = implode(', ', array_keys($item));
            $values = array_values($item);
            $binds = rtrim(str_repeat('?,', count($values)), ',');
            $query = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $names, $binds);

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);
            if($stmt->errorCode() !== '00000')
            {
                throw new AppException(sprintf(' - error %s', implode(', ', $stmt->errorInfo())));
            }
        }
        catch(\Throwable $throwable)
        {
            if($this->pdo->inTransaction())
            {
                $this->pdo->rollBack();
            }

            throw new AppException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
        if($this->pdo->inTransaction())
        {
            $this->pdo->commit();
        }
    }

    /**
     * @param string $table
     * @param array $item
     * @throws AppException
     */
    public function update(string $table, array $item)
    {
        try
        {
            if(!$this->pdo->inTransaction())
            {
                $this->pdo->beginTransaction();
            }

            $updates = '';
            foreach($item as $key => $value)
            {
                $updates .= sprintf('%s=?, ', $key);
            }
            $query = sprintf('UPDATE %s SET %s', $table, trim($updates, ', '));
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array_values($item));

            if($stmt->errorCode() !== '00000')
            {
                throw new AppException(sprintf(' - error %s', implode(', ', $stmt->errorInfo())));
            }
        }
        catch(\Throwable $throwable)
        {
            if($this->pdo->inTransaction())
            {
                $this->pdo->rollBack();
            }

            throw new AppException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
        if($this->pdo->inTransaction())
        {
            $this->pdo->commit();
        }
    }

    /**
     * @param string $table
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param array $binds
     * @return array
     * @throws AppException
     */
    public function select(string $table, string $where = '', string $order = '', string $limit = '', array $binds = [])
    {
        $query = sprintf('SELECT * FROM `%s` %s %s %s', $table, $where, $order, $limit);

        $stmt = $this->pdo->prepare($query);
        if(!$binds)
        {
            $stmt->execute();
        }
        else
        {
            $stmt->execute($binds);
        }
        if($stmt->errorCode() !== '00000')
        {
            throw new AppException(sprintf(' - error %s', implode(', ', $stmt->errorInfo())));
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

    public function truncate(string $table)
    {
        $this->pdo->exec(sprintf('TRUNCATE %s', $table));
    }
}