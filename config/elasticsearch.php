<?php

return [
    'hosts' => explode(',', env('ELASTICSEARCH_HOSTS', 'localhost:9200')),
    'retries' => env('ELASTICSEARCH_RETRIES', 2),
];
