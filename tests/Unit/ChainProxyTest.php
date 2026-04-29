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

namespace Tests\Unit;

use Api\Account\AccountInfo\AdvertiserInfo;
use Api\JuLiangQianChuan\AdvertisingMgmt\AccountBudget\AccountBudgetGet;
use Api\Tools\AppManagement\WorkspaceUpgrade\ToolsEbpAppList;
use Api\Tools\ByteMiniAppManagement\WorkspaceUpgrade\ToolsEbpWechatAppletList;
use Api\Tools\CommentMgmt\BlockedWordsUsers\ToolsCommentTermsBannedAdd;
use Core\Exception\OceanEngineException;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ChainProxyTest extends TestCase
{
    public function testPropertyAndMethodChainResolveSameRequestClass(): void
    {
        $client = new OceanEngineClient('token');

        $byProperty = $client->module('Account')
            ->AccountInfo
            ->AdvertiserInfo();

        $byMethod = $client->module('Account')
            ->AccountInfo()
            ->AdvertiserInfo();

        self::assertInstanceOf(AdvertiserInfo::class, $byProperty);
        self::assertInstanceOf(AdvertiserInfo::class, $byMethod);
    }

    public function testRecursiveShortPathResolutionWorks(): void
    {
        $client = new OceanEngineClient('token');

        $request = $client->module('JuLiangQianChuan')
            ->AccountBudget
            ->AccountBudgetGet();

        self::assertInstanceOf(
            AccountBudgetGet::class,
            $request
        );
    }

    public function testCommentCompatibilityCallResolvesChildDirectoryClass(): void
    {
        $client = new OceanEngineClient('token');

        $compatRequest = $client->module('Tools')
            ->CommentMgmt
            ->ToolsCommentTermsBannedAdd();

        $explicitRequest = $client->module('Tools')
            ->CommentMgmt
            ->BlockedWordsUsers
            ->ToolsCommentTermsBannedAdd();

        self::assertInstanceOf(
            ToolsCommentTermsBannedAdd::class,
            $compatRequest
        );
        self::assertInstanceOf(
            ToolsCommentTermsBannedAdd::class,
            $explicitRequest
        );
    }

    public function testAmbiguousShortModulePathThrowsExplicitError(): void
    {
        $client = new OceanEngineClient('token');

        $this->expectException(OceanEngineException::class);
        $this->expectExceptionMessage('子模块 WorkspaceUpgrade 存在歧义');

        $client->module('Tools')->WorkspaceUpgrade;
    }

    public function testExplicitWorkspaceUpgradePathsRemainReachable(): void
    {
        $client = new OceanEngineClient('token');

        $appManagementRequest = $client->module('Tools')
            ->AppManagement
            ->WorkspaceUpgrade
            ->ToolsEbpAppList();

        $byteMiniAppRequest = $client->module('Tools')
            ->ByteMiniAppManagement
            ->WorkspaceUpgrade
            ->ToolsEbpWechatAppletList();

        self::assertInstanceOf(ToolsEbpAppList::class, $appManagementRequest);
        self::assertInstanceOf(ToolsEbpWechatAppletList::class, $byteMiniAppRequest);
    }

    public function testAmbiguousShortRequestClassThrowsExplicitError(): void
    {
        $client = new OceanEngineClient('token');

        $this->expectException(OceanEngineException::class);
        $this->expectExceptionMessage('请求类 QianChuanReportCustomGet 存在歧义');

        $client->module('DataReports')->QianChuanReportCustomGet();
    }

    public function testUnknownNestedCallThrowsBadMethodCallException(): void
    {
        $client = new OceanEngineClient('token');

        $this->expectException(\BadMethodCallException::class);

        $client->module('Account')
            ->AccountInfo
            ->MethodNotExists();
    }
}
