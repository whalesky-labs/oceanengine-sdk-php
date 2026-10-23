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

use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpMaterialAuthCreate;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpMaterialAuthDelete;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpMaterialAuthList;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpVideoAttributesList;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpVideoDelete;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpVideoUpdate;
use Api\Tools\VideoMaterialManagement\WorkspaceUpgrade\ToolsEbpVideoUpload;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class WorkspaceVideoMaterialRequestTest extends TestCase
{
    /**
     * @dataProvider requestProvider
     */
    public function testRequestParametersArePassedThroughUnchanged(string $class): void
    {
        $params = [
            'account_id' => 'not-validated-by-sdk',
            'account_type' => 'CUSTOM_VALUE',
            'labels' => ['label'],
            'filtering' => ['material_ids' => [1, 2]],
            'unknown_field' => ['nested' => true],
        ];
        $request = new $class();

        $request->setParams($params);
        $request->check();

        self::assertSame($params, $request->getParams());
    }

    public function requestProvider(): array
    {
        return [
            'upload' => [ToolsEbpVideoUpload::class],
            'update' => [ToolsEbpVideoUpdate::class],
            'delete' => [ToolsEbpVideoDelete::class],
            'auth_create' => [ToolsEbpMaterialAuthCreate::class],
            'auth_list' => [ToolsEbpMaterialAuthList::class],
            'auth_delete' => [ToolsEbpMaterialAuthDelete::class],
            'attributes_list' => [ToolsEbpVideoAttributesList::class],
        ];
    }
}
