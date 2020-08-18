<?php

$url = 'https://www.edsm.net/api-v1/sphere-systems';

$parameters = [
    'systemName' => 'HIP 9216',
    'radius' => 40,
];

$encodedParameters = http_build_query($parameters);
$contents = file_get_contents($url . '?' . $encodedParameters);

$systems = json_decode($contents);

var_dump($systems);
