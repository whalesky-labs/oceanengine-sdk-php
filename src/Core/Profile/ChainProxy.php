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

namespace Core\Profile;

use Core\Exception\OceanEngineException;
use OceanEngineSDK\OceanEngineClient;

class ChainProxy extends BaseModule
{
    private string $namespace;

    /**
     * @var array<string, self>
     */
    private array $instances = [];

    /**
     * @var array<string, array<string, list<string>>>
     */
    private static array $providerCache = [];

    /**
     * @var array<string, array<string, list<string>>>
     */
    private static array $childClassCache = [];

    /**
     * 构造链式模块代理。
     *
     * @param OceanEngineClient $client SDK 客户端实例
     * @param string $namespace 当前链式节点命名空间
     */
    public function __construct(OceanEngineClient $client, string $namespace)
    {
        parent::__construct($client);
        $this->namespace = trim($namespace, '\\');
    }

    /**
     * 动态访问子模块节点。
     *
     * @param string $name 子模块名称
     * @return self
     * @throws OceanEngineException
     */
    public function __get(string $name): self
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $providerNamespace = $this->resolveProviderNamespace($name);
        if ($providerNamespace === null) {
            throw new OceanEngineException("Undefined property {$name}", 500);
        }

        $this->instances[$name] = new self($this->client, $providerNamespace);

        return $this->instances[$name];
    }

    /**
     * 动态调用请求类或继续向下解析子模块。
     *
     * @param string $name 方法名
     * @param array<int, mixed> $arguments 方法参数
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        $classBaseName = ucfirst($name);
        $className = $this->namespace . '\\' . $classBaseName;
        $classFile = $this->namespaceToDir($this->namespace) . '/' . $classBaseName . '.php';

        if (is_file($classFile) && class_exists($className)) {
            $instance = new $className($this->client);

            if (! empty($arguments) && method_exists($instance, $name)) {
                return call_user_func_array([$instance, $name], $arguments);
            }

            return $instance;
        }

        // 兼容历史行为：支持在当前节点直接调用子目录中的请求类（如 CommentMgmt->ToolsCommentTermsBannedAdd）。
        $childClassName = $this->resolveChildClass($classBaseName);
        if ($childClassName !== null) {
            return new $childClassName($this->client);
        }

        try {
            return $this->__get($name);
        } catch (OceanEngineException $e) {
            if (! str_starts_with($e->getMessage(), 'Undefined property ')) {
                throw $e;
            }

            throw new \BadMethodCallException("Class {$className} not found in {$this->namespace}");
        }
    }

    /**
     * 扫描并缓存当前命名空间下可访问的子模块。
     *
     * @return array<string, list<string>>
     */
    private function discoverProviders(): array
    {
        if (isset(self::$providerCache[$this->namespace])) {
            return self::$providerCache[$this->namespace];
        }

        $baseDir = $this->namespaceToDir($this->namespace);
        if ($baseDir === '' || ! is_dir($baseDir)) {
            self::$providerCache[$this->namespace] = [];
            return [];
        }

        $providers = [];
        $this->scanDirectory($baseDir, $this->namespace, '', $providers);

        self::$providerCache[$this->namespace] = $providers;

        return $providers;
    }

    /**
     * 递归扫描目录，构建子模块映射。
     *
     * @param string $baseDir 扫描基目录
     * @param string $baseNamespace 基础命名空间
     * @param string $relativePath 相对路径
     * @param array<string, list<string>> $providers
     * @return void
     */
    private function scanDirectory(string $baseDir, string $baseNamespace, string $relativePath, array &$providers): void
    {
        $currentDir = $baseDir . ($relativePath !== '' ? '/' . $relativePath : '');
        $currentNamespace = $baseNamespace . ($relativePath !== '' ? '\\' . str_replace('/', '\\', $relativePath) : '');

        $items = scandir($currentDir);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $currentDir . '/' . $item;
            if (! is_dir($path)) {
                continue;
            }

            $providers[$item] ??= [];
            $providers[$item][] = $currentNamespace . '\\' . $item;

            $newRelativePath = $relativePath !== '' ? $relativePath . '/' . $item : $item;
            $this->scanDirectory($baseDir, $baseNamespace, $newRelativePath, $providers);
        }
    }

    /**
     * 将命名空间转换为 SDK Api 目录路径。
     *
     * @param string $namespace 命名空间
     * @return string
     */
    private function namespaceToDir(string $namespace): string
    {
        $prefix = 'Api';
        if ($namespace !== $prefix && ! str_starts_with($namespace, $prefix . '\\')) {
            return '';
        }

        $relative = substr($namespace, strlen($prefix));
        $relative = ltrim(str_replace('\\', '/', $relative), '/');

        $apiBaseDir = dirname(__DIR__, 2) . '/Api';

        return $relative === '' ? $apiBaseDir : $apiBaseDir . '/' . $relative;
    }

    /**
     * 解析当前节点下的子目录请求类。
     *
     * @param string $classBaseName 类名（不含命名空间）
     * @return null|string
     */
    private function resolveChildClass(string $classBaseName): ?string
    {
        $childClasses = $this->discoverChildClasses();
        if (! isset($childClasses[$classBaseName])) {
            return null;
        }

        return $this->resolveUniqueTarget(
            $classBaseName,
            $childClasses[$classBaseName],
            '请求类'
        );
    }

    /**
     * 发现并缓存子目录中的请求类。
     *
     * @return array<string, list<string>>
     */
    private function discoverChildClasses(): array
    {
        if (isset(self::$childClassCache[$this->namespace])) {
            return self::$childClassCache[$this->namespace];
        }

        $baseDir = $this->namespaceToDir($this->namespace);
        if ($baseDir === '' || ! is_dir($baseDir)) {
            self::$childClassCache[$this->namespace] = [];
            return [];
        }

        $classes = [];
        $items = scandir($baseDir);
        if ($items === false) {
            self::$childClassCache[$this->namespace] = [];
            return [];
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $childDir = $baseDir . '/' . $item;
            if (! is_dir($childDir)) {
                continue;
            }

            foreach (glob($childDir . '/*.php') ?: [] as $file) {
                $classBaseName = pathinfo($file, PATHINFO_FILENAME);
                if ($classBaseName === '') {
                    continue;
                }

                $classes[$classBaseName] ??= [];
                $classes[$classBaseName][] = $this->namespace . '\\' . $item . '\\' . $classBaseName;
            }
        }

        self::$childClassCache[$this->namespace] = $classes;

        return $classes;
    }

    private function resolveProviderNamespace(string $name): ?string
    {
        $providers = $this->discoverProviders();
        if (! isset($providers[$name])) {
            return null;
        }

        return $this->resolveUniqueTarget($name, $providers[$name], '子模块');
    }

    /**
     * @param list<string> $candidates
     */
    private function resolveUniqueTarget(string $name, array $candidates, string $targetType): string
    {
        $uniqueCandidates = array_values(array_unique($candidates));
        if (count($uniqueCandidates) === 1) {
            return $uniqueCandidates[0];
        }

        throw new OceanEngineException(
            sprintf(
                '%s %s 存在歧义，请使用显式父级路径。可选路径: %s',
                $targetType,
                $name,
                implode(', ', $uniqueCandidates)
            ),
            500
        );
    }
}
