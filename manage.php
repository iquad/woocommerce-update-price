<?php

require 'vendor/autoload.php';

use Medoo\Medoo;
use League\Csv\Reader;

// prepare for data
$database = new Medoo([
    'database_type' => 'database_type',
    'database_name' => 'database_name',
    'server' => 'database_server',
    'username' => 'database_user',
    'password' => 'database_password',
    'charset' => 'database_charset',
]);

if (!ini_get("auto_detect_line_endings")) {
    ini_set("auto_detect_line_endings", '1');
}

$csv = Reader::createFromPath('xxx.csv')->setDelimiter(";");

$res = $csv->fetchAll();

foreach ($res as $r){
    $code       = $r[0];
    $newPrice   = $r[2];

    $veri = $database->select("wp_postmeta", ['post_id'], [
        "meta_value" => $code
    ]);
    if (empty($veri[0])) {
        continue;
    }
    $post_id    = $veri[0]['post_id'];
    $update = $database->update('wp_postmeta', [
        "meta_value"   => $newPrice
    ], [
        "meta_key"  => ['_price', '_sale_price'],
        "post_id"   => $post_id
    ]);
}
