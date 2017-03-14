<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest {

    error_reporting(E_ALL | E_STRICT);
    chdir(__DIR__);

    Bootstrap::init();
}

/**
 * Following namespace is a hack to re-establish support for PHP5.5 without
 * rewriting unit tests. New tests should be extending using "namespaced"
 * TestCase class.
 */
namespace PHPUnit\Framework {

    if (!class_exists('PHPUnit\Framework\TestCase')) {
        abstract class TestCase extends \PHPUnit_Framework_TestCase
        {
        }
    }

}
