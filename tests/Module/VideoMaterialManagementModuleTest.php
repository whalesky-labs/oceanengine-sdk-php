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

namespace Tests\Module;

use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpMaterialAuthCreate;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpMaterialAuthDelete;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpMaterialAuthList;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpVideoAttributesList;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpVideoDelete;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpVideoUpdate;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpVideoUpload;
use OceanEngineSDK\OceanEngineClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class VideoMaterialManagementModuleTest extends TestCase
{
    /**
     * @dataProvider requestProvider
     */
    public function testWorkspaceRequestsAreReachable(string $class, string $path, string $method, string $contentType): void
    {
        $client = new OceanEngineClient('token');
        $name = substr($class, strrpos($class, '\\') + 1);
        $request = $client->Tools()->VideoMaterialManagement->WorkspaceUpgrade->{$name}();
        $byMethod = $client->Tools()->VideoMaterialManagement()->WorkspaceUpgrade()->{$name}();
        $byShortPath = $client->Tools()->VideoMaterialManagement->{$name}();

        self::assertInstanceOf($class, $request);
        self::assertInstanceOf($class, $byMethod);
        self::assertInstanceOf($class, $byShortPath);
        self::assertSame($path, $request->getUrl());
        self::assertSame($method, $request->getMethod());
        self::assertSame($contentType, $request->getContentType());
    }

    public function requestProvider(): array
    {
        return [
            'upload' => [ToolsEbpVideoUpload::class, '/v3.0/tools/ebp/video/upload/', 'POST', 'multipart/form-data'],
            'update' => [ToolsEbpVideoUpdate::class, '/v3.0/tools/ebp/video/update/', 'POST', 'application/json'],
            'delete' => [ToolsEbpVideoDelete::class, '/v3.0/tools/ebp/video/delete/', 'POST', 'application/json'],
            'share' => [ToolsEbpMaterialAuthCreate::class, '/v3.0/tools/ebp/material/auth/create/', 'POST', 'application/json'],
            'list_shares' => [ToolsEbpMaterialAuthList::class, '/v3.0/tools/ebp/material/auth/list/', 'GET', 'application/json'],
            'cancel_share' => [ToolsEbpMaterialAuthDelete::class, '/v3.0/tools/ebp/material/auth/delete/', 'POST', 'application/json'],
            'attributes' => [ToolsEbpVideoAttributesList::class, '/v3.0/tools/ebp/video_attributes/list/', 'GET', 'application/json'],
        ];
    }
}
