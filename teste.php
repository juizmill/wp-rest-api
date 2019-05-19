<?php

include __DIR__.'/vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'https://www.larimoveis.com.br'
]);

$response = $client->get('comprar/casa/belo-horizonte/sao-bento/33459/casa-3-quartos-sao-bento-belo-horizonte/');

print_r($response->getStatusCode() );


