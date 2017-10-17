<?php

namespace App\Core;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

class DatabaseFactory
{
    /**
     * @return Connection
     */
    public function getDbConnection()
    {
        return DB::connection();
    }
}
