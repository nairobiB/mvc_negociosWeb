<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitac2d9af57f58614faa56a4064e8533c4
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitac2d9af57f58614faa56a4064e8533c4', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitac2d9af57f58614faa56a4064e8533c4', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitac2d9af57f58614faa56a4064e8533c4::getInitializer($loader));

        $loader->register(true);

        $filesToLoad = \Composer\Autoload\ComposerStaticInitac2d9af57f58614faa56a4064e8533c4::$files;
        $requireFile = static function ($fileIdentifier, $file) {
            if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
                $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

                require $file;
            }
        };
        foreach ($filesToLoad as $fileIdentifier => $file) {
            ($requireFile)($fileIdentifier, $file);
        }

        return $loader;
    }
}
