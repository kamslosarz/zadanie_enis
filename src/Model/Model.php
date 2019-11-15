<?php

namespace App\Model;

use App\AppException;
use App\Database\DatabaseFactory;

abstract class Model
{
    protected $data = [];

    /**
     * @return bool
     * @throws AppException
     */
    public function save(): bool
    {
        if(!$this->isValid())
        {
            return false;
        }

        $database = DatabaseFactory::getInstance();
        $database->insert($this->getTableName(), $this->data);
        $this->data[$this->getPrimaryKey()] = $database->getLastInsertId();

        return true;
    }

    /**
     * @return bool
     * @throws AppException
     */
    public function update()
    {
        if(!$this->isValid())
        {
            return false;
        }

        $database = DatabaseFactory::getInstance();
        $data = $this->data;
        unset($data[$this->getPrimaryKey()]);

        $database->update($this->getTableName(), $data);

        return true;
    }

    abstract protected function isValid(): bool;

    abstract protected function getTableName(): string;

    abstract protected function getPrimaryKey(): string;
}