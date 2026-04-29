<?php

declare(strict_types=1);
/**
 * This file is part of Marketing PHP SDK.
 *
 * @link     https://github.com/westng/oceanengine-sdk-php
 * @document https://github.com/westng/oceanengine-sdk-php
 * @contact  westng
 * @license  https://github.com/westng/oceanengine-sdk-php/blob/main/LICENSE
 */

namespace Core\Autoloader;

class Autoloader
{
    /**
     * 命名空间到源码路径前缀映射（基于 src 目录）.
     *
     * @var array<string, string>
     */
    private static array $replacePath = [
        'OceanEngineSDK\\' => 'Core\Profile\\',
        'Core\\' => 'Core\\',
        'Api\\' => 'Api\\',
        'Oauth\\' => 'Oauth\\',
        // 历史别名兼容
        'AdOauth\\' => 'Oauth\\',
        'Account\\' => 'Api\Account\\',
        'DataReports\\' => 'Api\DataReports\\',
        'Tools\\' => 'Api\Tools\\',
        'Materials\\' => 'Api\Materials\\',
        'EnterpriseAccount\\' => 'Api\EnterpriseAccount\\',
        'JuLiangAds\\' => 'Api\JuLiangAds\\',
        'JuLiangLocalPush\\' => 'Api\JuLiangLocalPush\\',
        'JuLiangQianChuan\\' => 'Api\JuLiangQianChuan\\',
        'JuLiangStarMap\\' => 'Api\JuLiangStarMap\\',
    ];

    /**
     * @var array<string, string>
     */
    private static array $dynamicPathMap = [];

    /**
     * @var array<string, null|string>
     */
    private static array $resolvedFileCache = [];

    /**
     * 自动加载类.
     *
     * @param string $className 类全限定名
     * @return void
     */
    public static function autoload(string $className): void
    {
        if (array_key_exists($className, self::$resolvedFileCache)) {
            $file = self::$resolvedFileCache[$className];
            if ($file !== null) {
                include_once $file;
            }
            return;
        }

        if (str_starts_with($className, 'Tests\\')) {
            $file = self::resolveTestFile($className);
            self::$resolvedFileCache[$className] = $file;
            if ($file !== null) {
                include_once $file;
            }
            return;
        }

        if (! self::isProjectClass($className)) {
            return;
        }

        $normalizedClass = self::normalizeClassName($className);
        if ($normalizedClass === null) {
            self::$resolvedFileCache[$className] = null;
            return;
        }

        $srcDir = dirname(__DIR__, 2);
        $file = $srcDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $normalizedClass) . '.php';
        if (is_file($file)) {
            self::$resolvedFileCache[$className] = $file;
            include_once $file;
            return;
        }

        self::$resolvedFileCache[$className] = null;
    }

    /**
     * 加载当前目录下的所有子目录（用于补充命名空间映射）.
     *
     * @return void
     */
    public static function loadDirectories(): void
    {
        $srcDir = dirname(__DIR__, 2);
        foreach (glob($srcDir . DIRECTORY_SEPARATOR . '*') ?: [] as $directory) {
            if (! is_dir($directory)) {
                continue;
            }

            $name = basename($directory);
            $prefix = $name . '\\';
            if (! isset(self::$replacePath[$prefix])) {
                self::$dynamicPathMap[$prefix] = $prefix;
            }
        }
    }

    /**
     * 添加新的自动加载路径.
     *
     * @param string $path 命名空间路径
     * @return void
     */
    public static function addAutoloadPath(string $path): void
    {
        $trimmed = trim($path, ' \/\\');
        if ($trimmed === '') {
            return;
        }

        $normalized = str_replace('/', '\\', $trimmed) . '\\';
        self::$dynamicPathMap[$normalized] = $normalized;
    }

    /**
     * 判断类名是否属于当前 SDK 命名空间。
     *
     * @param string $className 类全限定名
     * @return bool
     */
    private static function isProjectClass(string $className): bool
    {
        $prefixes = array_merge(array_keys(self::$replacePath), array_keys(self::$dynamicPathMap));
        foreach ($prefixes as $prefix) {
            if (str_starts_with($className, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 将传入类名映射为 src 目录下的相对类路径。
     *
     * @param string $className 类全限定名
     * @return null|string
     */
    private static function normalizeClassName(string $className): ?string
    {
        $pathMap = array_merge(self::$replacePath, self::$dynamicPathMap);

        foreach ($pathMap as $namespace => $replacement) {
            if (! str_starts_with($className, $namespace)) {
                continue;
            }

            return $replacement . substr($className, strlen($namespace));
        }

        return null;
    }

    /**
     * 解析 tests 命名空间对应的本地测试文件路径。
     *
     * @param string $className 类全限定名
     * @return null|string
     */
    private static function resolveTestFile(string $className): ?string
    {
        $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, substr($className, 6));
        $testFile = dirname(dirname(__DIR__, 2)) . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . $relativePath . '.php';

        return is_file($testFile) ? $testFile : null;
    }
}

// 注册自动加载器（不抛出异常，让其他自动加载器有机会处理）
spl_autoload_register(['Core\Autoloader\Autoloader', 'autoload'], true, false);
