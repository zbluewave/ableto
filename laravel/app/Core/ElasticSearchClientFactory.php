<?php
/**
 * Created by PhpStorm.
 * User: hzhang
 * Date: 10/15/17
 * Time: 11:51 PM
 */

namespace App\Core;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;

class ElasticSearchClientFactory
{
    /**
     * @return Client
     */
    public function getClient()
    {
        return ClientBuilder::create()
            ->setHosts([
                '127.0.0.1:9200'
            ])->build();
    }
}
