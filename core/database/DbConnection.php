<?php
namespace core\database;

use core\Config as Config;

/**
 * Iskus Anton. Email: iskus1981@yandex.ru
 * IDE PhpStorm. 12.04.2015
 */
class DbConnection
{//extends PDO {
    public function __construct($driver)
    {
        $this->config = Config::getDbConfig($driver);
        $this->driver = $driver;

    }

    /**
     * Run this private methods (mysql|mongo|others...)Connect()
     * @return object DbConnection
     */
    public function connect()
    {
        return $this->{"{$this->driver}Connect"}();
    }

    /**
     * @return MysqlDbConnection
     */
    private function mysqlConnect()
    {
        return new MysqlDbConnection(
            $this->config->host,
            $this->config->user,
            $this->config->pass,
            $this->config->db
        );
    }

    private function mongoConnect()
    {

    }
}