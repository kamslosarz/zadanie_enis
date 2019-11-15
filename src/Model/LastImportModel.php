<?php


namespace App\Model;


use App\AppException;
use App\Database\DatabaseFactory;

class LastImportModel extends Model
{
    public function setDone(int $done): self
    {
        $this->data['done'] = $done;

        return $this;
    }

    public function setTotal(int $total): self
    {
        $this->data['total'] = $total;

        return $this;
    }

    public function setDate(string $date): self
    {
        $this->data['date'] = $date;

        return $this;
    }

    /**
     * @return array
     * @throws AppException
     */
    public function getLast()
    {
        $database = DatabaseFactory::getInstance();
        $items = $database->select($this->getTableName(), '','ORDER BY id DESC', 'LIMIT 0,1');

        return isset($items[0])? $items[0] : null;
    }

    protected function isValid(): bool
    {
        return true;
    }

    protected function getTableName(): string
    {
        return 'import';
    }

    protected function getPrimaryKey(): string
    {
        return 'id';
    }
}