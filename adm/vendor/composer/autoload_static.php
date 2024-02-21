<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit51f3feed83502eb04911988729e12c2c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'C' => 
        array (
            'Core\\' => 5,
        ),
        'A' => 
        array (
            'App\\' => 4,
            'Adms\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Adms\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/adms',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit51f3feed83502eb04911988729e12c2c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit51f3feed83502eb04911988729e12c2c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit51f3feed83502eb04911988729e12c2c::$classMap;

        }, null, ClassLoader::class);
    }
}