<?php

return [
    'hosts' => explode(',', env('ELASTICSEARCH_HOSTS', '127.0.0.1:9200')),
    'retries' => 1,

    'index_posts' => env('INDEX_POSTS'),
];
