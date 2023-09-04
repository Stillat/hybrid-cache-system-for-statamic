<?php

return [
    'ignore_uri_patterns' => [
        '/!/.*?',
        '/cp',
        '/cp/.*?',
        '/api/.*?',
        '/contact',
    ],
    'cache_response_codes' => [
        200,
        301,
        302,
        303,
        307,
        308,
    ],
    'ignore_query_strings' => true,
];
