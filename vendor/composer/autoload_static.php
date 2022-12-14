<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite9b9137efce611078dd0dda2d3ceacd5
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '8825ede83f2f289127722d4e842cf7e8' => __DIR__ . '/..' . '/symfony/polyfill-intl-grapheme/bootstrap.php',
        'e69f7f6ee287b969198c3c9d6777bd38' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/bootstrap.php',
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        'b6b991a57620e2fb6b2f66f03fe9ddc2' => __DIR__ . '/..' . '/symfony/string/Resources/functions.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        '7d8c19660fc7bda7e3f1bb627c20c455' => __DIR__ . '/..' . '/yooper/stop-words/src/StopWordFactory.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
        '82b15671fa4352bd2c1ea8902d4c0c5d' => __DIR__ . '/..' . '/yooper/php-text-analysis/src/helpers/storage.php',
        'c2fe535f6d51f069823351f60bd6b280' => __DIR__ . '/..' . '/yooper/php-text-analysis/src/helpers/print.php',
        '34faac671c44560451a381662d8b697c' => __DIR__ . '/..' . '/yooper/php-text-analysis/src/helpers/simplified.php',
        '97c3b78656a7c2fa22581078400c5264' => __DIR__ . '/..' . '/yooper/php-text-analysis/src/helpers/helpers.php',
        '701340487703d077fa1bf3462f5a1343' => __DIR__ . '/..' . '/yooper/php-text-analysis/src/helpers/interactive_help.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'Yooper\\' => 7,
        ),
        'W' => 
        array (
            'Wamania\\Snowball\\' => 17,
        ),
        'T' => 
        array (
            'TextAnalysis\\' => 13,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Intl\\Normalizer\\' => 33,
            'Symfony\\Polyfill\\Intl\\Grapheme\\' => 31,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Contracts\\Service\\' => 26,
            'Symfony\\Component\\String\\' => 25,
            'Symfony\\Component\\Console\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
            'Psr\\Container\\' => 14,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Yooper\\' => 
        array (
            0 => __DIR__ . '/..' . '/yooper/nicknames/src',
        ),
        'Wamania\\Snowball\\' => 
        array (
            0 => __DIR__ . '/..' . '/wamania/php-stemmer/src',
        ),
        'TextAnalysis\\' => 
        array (
            0 => __DIR__ . '/..' . '/yooper/php-text-analysis/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Intl\\Normalizer\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer',
        ),
        'Symfony\\Polyfill\\Intl\\Grapheme\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-grapheme',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Contracts\\Service\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/service-contracts',
        ),
        'Symfony\\Component\\String\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/string',
        ),
        'Symfony\\Component\\Console\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/console',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
            1 => __DIR__ . '/..' . '/psr/http-factory/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Normalizer' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite9b9137efce611078dd0dda2d3ceacd5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite9b9137efce611078dd0dda2d3ceacd5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite9b9137efce611078dd0dda2d3ceacd5::$classMap;

        }, null, ClassLoader::class);
    }
}
