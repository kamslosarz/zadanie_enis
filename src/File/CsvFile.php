<?php


namespace App\File;


use App\AppException;

class CsvFile extends File
{
    protected $data = [];
    protected $columns = [];

    /**
     * @param int $length
     * @param string $delimiter
     */
    public function read($length = 1000, $delimiter = ';'): void
    {
        $this->columns = null;
        $this->data = [];
        $handle = fopen($this->filename, 'r');
        while(($line = fgetcsv($handle, 1000, ';')) !== false)
        {
            if(!$this->columns)
            {
                $this->columns = array_flip($line);
            }
            else
            {
                $this->data[] = $line;
            }
        }
        fclose($handle);
    }

    /**
     * @param string $name
     * @return int
     */
    public function getColumnIndex(string $name): int
    {
        return $this->columns[$name];
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return count($this->data);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param array $items
     * @param string $delimiter
     * @throws AppException
     */
    public function save(array $items, string $delimiter): void
    {
        $handle = fopen($this->filename, 'w');
        try
        {
            fputcsv($handle, $this->columns, $delimiter);
            foreach($items as $item)
            {
                fputcsv($handle, $item, $delimiter);
            }
            fclose($handle);
        }
        catch(\Throwable $throwable)
        {
            fclose($handle);
            throw new AppException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }
}