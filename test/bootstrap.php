<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

chdir(__DIR__);

$loader = null;

if (file_exists('../vendor/autoload.php')) {
    $loader = include '../vendor/autoload.php';
} elseif (file_exists('../../../autoload.php')) {
    $loader = include '../../../autoload.php';
} else {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

$loader->add('MtMailTest', __DIR__);
