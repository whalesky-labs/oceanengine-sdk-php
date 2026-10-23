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
$changelogPath = $root . '/CHANGELOG.md';
$command = $argv[1] ?? 'validate';

function fail(string $message): void
{
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}

function readChangelog(string $path): string
{
    if (! is_file($path)) {
        fail('CHANGELOG.md 不存在。');
    }

    $content = file_get_contents($path);
    if ($content === false) {
        fail('无法读取 CHANGELOG.md。');
    }

    return $content;
}

/** @return list<array{name: string, raw: string, body: string}> */
function parseSections(string $content): array
{
    preg_match_all('/^## \[([^\]]+)\][^\r\n]*\R?/m', $content, $matches, PREG_OFFSET_CAPTURE);
    $sections = [];
    $count = count($matches[0]);

    for ($index = 0; $index < $count; ++$index) {
        $start = $matches[0][$index][1];
        $end = $index + 1 < $count ? $matches[0][$index + 1][1] : strlen($content);
        $raw = trim(substr($content, $start, $end - $start));
        $headerLength = strlen($matches[0][$index][0]);

        $sections[] = [
            'name' => trim($matches[1][$index][0]),
            'raw' => $raw,
            'body' => trim(substr($raw, $headerLength)),
        ];
    }

    return $sections;
}

function normalizeVersion(string $version): string
{
    return ltrim(trim($version), 'v');
}

function versionFromArguments(array $arguments): string
{
    $versionIndex = array_search('--version', $arguments, true);
    if ($versionIndex === false || ! isset($arguments[$versionIndex + 1])) {
        fail('必须通过 --version 指定版本号。');
    }

    $version = normalizeVersion((string) $arguments[$versionIndex + 1]);
    if (! preg_match('/^\d+\.\d+\.\d+$/', $version)) {
        fail('版本号不是合法的语义化版本：' . $version);
    }

    return $version;
}

/** @return list<string> */
function categories(string $body): array
{
    preg_match_all('/^### ([^\r\n]+)\s*$/m', $body, $matches);
    return array_map('trim', $matches[1]);
}

function findVersionSection(array $sections, string $version): ?array
{
    foreach ($sections as $section) {
        if ($section['name'] !== '未发布' && normalizeVersion($section['name']) === $version) {
            return $section;
        }
    }

    return null;
}

function validateSections(array $sections, ?string $version = null): void
{
    if ($sections === [] || $sections[0]['name'] !== '未发布') {
        fail('CHANGELOG.md 的第一段必须是 [未发布]。');
    }

    $allowedCategories = [
        '新增',
        '修复',
        '变更',
        '文档',
        '测试',
        '构建',
        '移除',
        '废弃',
        '安全',
        '性能',
    ];
    $seenVersions = [];

    foreach ($sections as $section) {
        if ($section['name'] !== '未发布') {
            $normalizedVersion = normalizeVersion($section['name']);
            if (! preg_match('/^\d+\.\d+\.\d+$/', $normalizedVersion)) {
                fail('CHANGELOG.md 中存在非法版本段：[' . $section['name'] . ']。');
            }
            if (isset($seenVersions[$normalizedVersion])) {
                fail('CHANGELOG.md 中存在重复版本段：[' . $normalizedVersion . ']。');
            }
            $seenVersions[$normalizedVersion] = true;
        }

        $sectionCategories = categories($section['body']);
        if ($sectionCategories === []) {
            fail('版本 [' . $section['name'] . '] 至少需要一个分类标题。');
        }
        if (! preg_match('/^-\s+\S/m', $section['body'])) {
            fail('版本 [' . $section['name'] . '] 至少需要一个实际变更条目。');
        }
        foreach ($sectionCategories as $category) {
            if (! in_array($category, $allowedCategories, true)) {
                fail('版本 [' . $section['name'] . '] 使用了不支持的分类：' . $category . '。');
            }
        }
    }

    if ($version !== null && findVersionSection($sections, $version) === null) {
        fail('CHANGELOG.md 缺少版本 [' . $version . '] 的变更内容。');
    }
}

$content = readChangelog($changelogPath);
$sections = parseSections($content);

if ($command === 'validate') {
    $version = in_array('--version', $argv, true) ? versionFromArguments($argv) : null;
    validateSections($sections, $version);
    fwrite(STDOUT, "CHANGELOG.md 格式校验通过。\n");
    exit(0);
}

if ($command === 'extract') {
    $version = versionFromArguments($argv);
    validateSections($sections, $version);
    $section = findVersionSection($sections, $version);
    if ($section === null) {
        fail('CHANGELOG.md 缺少版本 [' . $version . '] 的变更内容。');
    }
    fwrite(STDOUT, $section['raw'] . PHP_EOL);
    exit(0);
}

if ($command === 'changed') {
    $baseIndex = array_search('--base', $argv, true);
    $headIndex = array_search('--head', $argv, true);
    if ($baseIndex === false || ! isset($argv[$baseIndex + 1]) || $headIndex === false || ! isset($argv[$headIndex + 1])) {
        fail('changed 命令需要 --base 和 --head。');
    }

    $base = (string) $argv[$baseIndex + 1];
    $head = (string) $argv[$headIndex + 1];
    if ($base === '' || preg_match('/^0+$/', $base)) {
        fwrite(STDOUT, "无法比较空的基线提交，跳过变更日志同步检查。\n");
        exit(0);
    }

    $commandLine = sprintf(
        'git -C %s diff --name-only %s...%s 2>&1',
        escapeshellarg($root),
        escapeshellarg($base),
        escapeshellarg($head),
    );
    exec($commandLine, $output, $exitCode);
    if ($exitCode !== 0) {
        fail("无法读取提交范围，不能完成 CHANGELOG.md 同步检查。\n" . implode(PHP_EOL, $output));
    }

    $changedFiles = array_values(array_filter(array_map('trim', $output)));
    $hasOtherChanges = array_filter($changedFiles, static fn (string $file): bool => $file !== 'CHANGELOG.md');
    if ($hasOtherChanges !== [] && ! in_array('CHANGELOG.md', $changedFiles, true)) {
        fail("本次提交包含项目变更，但没有同步更新 CHANGELOG.md。\n变更文件：" . implode(', ', $changedFiles));
    }

    if ($hasOtherChanges !== []) {
        $changelogDiffCommand = sprintf(
            'git -C %s diff --unified=0 --no-ext-diff %s...%s -- CHANGELOG.md 2>&1',
            escapeshellarg($root),
            escapeshellarg($base),
            escapeshellarg($head),
        );
        exec($changelogDiffCommand, $changelogDiff, $changelogDiffExitCode);
        if ($changelogDiffExitCode !== 0) {
            fail("无法读取 CHANGELOG.md 的变更内容，不能完成同步检查。\n" . implode(PHP_EOL, $changelogDiff));
        }

        $hasEntryChange = false;
        foreach ($changelogDiff as $line) {
            if (str_starts_with($line, '+++')) {
                continue;
            }
            if (preg_match('/^\+\s*(?:-\s+\S|## \[(?:未发布|\d+\.\d+\.\d+)\]|###\s+\S)/u', $line)) {
                $hasEntryChange = true;
                break;
            }
        }

        if (! $hasEntryChange) {
            fail('本次提交虽然修改了 CHANGELOG.md，但没有新增版本段、分类标题或变更条目。');
        }
    }

    fwrite(STDOUT, "CHANGELOG.md 已随本次项目变更同步。\n");
    exit(0);
}

fail('未知命令：' . $command . '。可用命令：validate、extract、changed。');
