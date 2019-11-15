<?php

namespace App\Database;

use App\AppException;

class DatabaseFactory
{
    const SQLITE_FILE = APP_DIR . '/data/database.sqlite';
    /** @var \PDO $pdo */
    protected static $pdo = null;

    /**
     * @return Database
     * @throws AppException
     */
    public static function getInstance(): Database
    {
        try
        {
            return new Database(self::getPdoInstance());
        }
        catch(\Throwable $throwable)
        {
            throw new AppException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }

    /**
     * @throws AppException
     */
    private static function getPdoInstance(): \PDO
    {
        if(!self::$pdo)
        {
            if(!file_exists(self::SQLITE_FILE) || !is_writeable(self::SQLITE_FILE))
            {
                throw new AppException('Database file is invalid');
            }
            if(file_exists(self::SQLITE_FILE . '-journal'))
            {
                throw new AppException(sprintf('Another process is running. Please delete the file %s', self::SQLITE_FILE . '-journal'));
            }

            self::$pdo = new \PDO(sprintf('sqlite:%s', self::SQLITE_FILE), null, null, [\PDO::ATTR_PERSISTENT => true]);
        }

        return self::$pdo;
    }

    public static function shutdown()
    {
        if(self::$pdo && self::$pdo->inTransaction())
        {
            self::$pdo->rollBack();
        }
        self::$pdo = null;
        if(file_exists(self::SQLITE_FILE . '-journal'))
        {
            unlink(self::SQLITE_FILE . '-journal');
        }
    }
}