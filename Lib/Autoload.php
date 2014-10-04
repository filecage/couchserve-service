<?php

    namespace couchService\Service\Lib;
    use couchService\Service\Lib\Exceptions\AutoloadException;

    class Autoload {

        const PATH_SEPARATOR = '/';
        const CLASS_SEPARATOR = '\\';
        const FILE_EXTENSION = '.php';

        public static function load($class) {
            $parts = explode(self::CLASS_SEPARATOR, $class);
            $filename = array_pop($parts);
            $path = implode(self::PATH_SEPARATOR, array_slice($parts, 2));

            $file = $path . self::PATH_SEPARATOR . $filename . self::FILE_EXTENSION;
            if (file_exists($file)) {
                include_once $file;
                return true;
            } else {
                throw new AutoloadException($class, $file);
            }
        }

    }