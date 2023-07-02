<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit38e1606492463bc93da552830a8ebe30
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'secureDB\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'secureDB\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit38e1606492463bc93da552830a8ebe30::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit38e1606492463bc93da552830a8ebe30::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit38e1606492463bc93da552830a8ebe30::$classMap;

        }, null, ClassLoader::class);
    }
}
