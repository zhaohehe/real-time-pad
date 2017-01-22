<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Models;

USE MongoDB\Client;
use Pad\Foundation\Redis\Redis;

class Base
{
    protected $table;

    protected $redis;

    protected $mongo;

    protected $model;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->setRedis();
        $this->setMongo();
        $db = $this->mongo->pad;    //init db

        $table = $this->table;    //get table name

        $this->model = $db->$table;

    }

    /**
     * set redis instance
     */
    protected function setRedis()
    {
        $client = new Redis();
        $this->redis = $client->getClient();
    }

    /**
     * set mongo db instance
     */
    protected function setMongo()
    {
        $mongo = new Client();
        $this->mongo = $mongo;
    }
}