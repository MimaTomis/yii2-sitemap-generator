<?php
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$loader = include __DIR__ . '/../vendor/autoload.php';

if (!$loader) {
    die('Load composer and install dependencies before test running');
}

define('TEMP_DIR', __DIR__.'/temp');
define('FIXTURES_DIR', __DIR__.'/fixtures');

$loader->add('SitemapGenerator\Tests', __DIR__);