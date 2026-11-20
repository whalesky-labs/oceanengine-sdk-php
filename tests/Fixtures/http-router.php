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
$acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? null;
$file = $_FILES['video_file'] ?? null;
$body = json_encode([
    'code' => 0,
    'method' => $_SERVER['REQUEST_METHOD'],
    'path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
    'accept_encoding' => $acceptEncoding,
    'query' => $_GET,
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? null,
    'json' => json_decode(file_get_contents('php://input'), true),
    'fields' => $_POST,
    'file' => $file !== null && $file['error'] === UPLOAD_ERR_OK ? [
        'name' => $file['name'],
        'type' => $file['type'],
        'sha256' => hash_file('sha256', $file['tmp_name']),
    ] : null,
], JSON_THROW_ON_ERROR);

header('Content-Type: application/json');
if ($acceptEncoding !== 'identity' && ! isset($_GET['plain'])) {
    header('Content-Encoding: deflate');
    $body = gzcompress($body);
}
header('Content-Length: ' . strlen($body));
echo $body;
