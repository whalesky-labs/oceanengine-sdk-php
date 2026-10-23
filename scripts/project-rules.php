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
$root = dirname(__DIR__);
$command = $argv[1] ?? 'validate';

function fail(string $message): void
{
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}

function readRequired(string $root, string $relativePath): string
{
    $path = $root . '/' . $relativePath;
    if (! is_file($path)) {
        fail('项目规则要求文件存在：' . $relativePath);
    }

    $content = file_get_contents($path);
    if ($content === false) {
        fail('无法读取项目规则文件：' . $relativePath);
    }

    return $content;
}

function requireText(string $relativePath, string $content, string $text): void
{
    if (strpos($content, $text) === false) {
        fail($relativePath . ' 缺少必需规则：' . $text);
    }
}

if ($command !== 'validate') {
    fail('未知命令：' . $command . '。可用命令：validate。');
}

$agents = readRequired($root, 'AGENTS.md');
$requiredSections = [
    '## 适用范围与优先级',
    '## 项目概览',
    '## API 接口封装规范',
    '## PHP、依赖与代码风格',
    '## 测试与验证',
    '## CHANGELOG 规范',
    '## 提交、推送与发布',
    '## 完成检查清单',
];
foreach ($requiredSections as $section) {
    requireText('AGENTS.md', $agents, $section);
}

$requiredRules = [
    'PHP `>=8.0`',
    '`RpcRequest`',
    '`setParams()`',
    '不得擅自加入业务参数',
    '业务字段校验',
    '自动 JSON 参数改写',
    '`CHANGELOG.md`',
    '`composer hooks:install`',
];
foreach ($requiredRules as $rule) {
    requireText('AGENTS.md', $agents, $rule);
}

$composerContent = readRequired($root, 'composer.json');
$composer = json_decode($composerContent, true);
if (! is_array($composer)) {
    fail('composer.json 不是有效的 JSON。');
}
if (($composer['require']['php'] ?? null) !== '>=8.0') {
    fail('composer.json 必须保持 PHP >=8.0。');
}
foreach (['rules:validate', 'changelog:validate', 'hooks:install'] as $script) {
    if (! isset($composer['scripts'][$script])) {
        fail('composer.json 缺少脚本：' . $script . '。');
    }
}

$ci = readRequired($root, '.github/workflows/ci.yml');
requireText('.github/workflows/ci.yml', $ci, 'scripts/project-rules.php validate');
requireText('.github/workflows/ci.yml', $ci, 'scripts/changelog.php validate');
requireText('.github/workflows/ci.yml', $ci, 'scripts/changelog.php changed');

$release = readRequired($root, '.github/workflows/release.yml');
requireText('.github/workflows/release.yml', $release, 'scripts/changelog.php validate --version');
requireText('.github/workflows/release.yml', $release, 'scripts/changelog.php extract --version');
requireText('.github/workflows/release.yml', $release, 'body_path: release-notes.md');
requireText('.github/workflows/release.yml', $release, 'generate_release_notes: false');

foreach (['.githooks/commit-msg', '.githooks/pre-commit', '.githooks/pre-push'] as $hook) {
    $hookContent = readRequired($root, $hook);
    if ($hook === '.githooks/commit-msg') {
        requireText($hook, $hookContent, 'Conventional Commits');
        continue;
    }

    requireText($hook, $hookContent, 'scripts/project-rules.php validate');
    requireText($hook, $hookContent, 'scripts/changelog.php validate');
}

fwrite(STDOUT, "项目规范校验通过。\n");
