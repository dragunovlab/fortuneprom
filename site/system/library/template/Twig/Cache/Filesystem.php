<?php

/*
 * This file is part of Twig.
 *
 * (c) 2015 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Stores compiled templates on the filesystem.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_Cache_Filesystem implements Twig_CacheInterface
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = rtrim($directory, '/\\') . '/';
    }

    public function generateKey($name, $className)
    {
        return $this->directory . $className . '.php';
    }

    public function write($key, $content)
    {
        $dir = dirname($key);

        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new RuntimeException(sprintf('Unable to create the cache directory (%s).', $dir));
            }
        } elseif (!is_writable($dir)) {
            throw new RuntimeException(sprintf('Unable to write in the cache directory (%s).', $dir));
        }

        $tmpFile = tempnam($dir, basename($key));

        if (false !== @file_put_contents($tmpFile, $content) && @rename($tmpFile, $key)) {
            @chmod($key, 0666 & ~umask());

            return;
        }

        throw new RuntimeException(sprintf('Failed to write cache file "%s".', $key));
    }

    public function load($key)
    {
        if (file_exists($key)) {
            @include_once $key;
        }
    }

    public function getTimestamp($key)
    {
        return file_exists($key) ? (int) filemtime($key) : 0;
    }
}
