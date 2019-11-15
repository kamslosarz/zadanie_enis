<?php


namespace App\File;

abstract class File
{
    protected $filename = '';

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function isReadable(): bool
    {
        return is_readable($this->filename);
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function isWriteable(): bool
    {
        return is_writeable($this->filename);
    }

    abstract public function read();
}