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

    protected $model;

    public function __construct()
    {
        $client = new Redis();
        $this->redis = $client->getClient();

        $mogodb = new Client();

        $db = $mogodb->pad;

        $table = $this->table;
        $this->model = $db->$table;

    }
}