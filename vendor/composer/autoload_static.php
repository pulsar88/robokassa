<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0dfe84f86ae71defbc9a98bdfb600321
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Fillincode\\Robokassa\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Fillincode\\Robokassa\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0dfe84f86ae71defbc9a98bdfb600321::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0dfe84f86ae71defbc9a98bdfb600321::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0dfe84f86ae71defbc9a98bdfb600321::$classMap;

        }, null, ClassLoader::class);
    }
}
