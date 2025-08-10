<?php 
require_once "./include/config.php";

/**
 * このファイルには、アプリケーションのヘルパー関数が含まれています。
 * This file contains helper functions for the application.
 */

 /**
  * リクエストメソッドがPOSTかどうかを確認します。
  * Checks if the request method is POST.
  */
function onlyPost()
{
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        res403();
    }
}

/**
 * リクエストメソッドがGETかどうかを確認します。
 * Checks if the request method is GET.
 */
function onlyGet()
{
    if($_SERVER['REQUEST_METHOD'] !== 'GET') {
        res403();
    }
}

/**
 * リクエストがAJAXリクエストかどうかを確認します。
 * Checks if the request is an AJAX request.
 */
function onlyAjax()
{
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        res403();
    }
}

/**
 * 403 Unauthorized レスポンスを送信します。
 * Sends a 403 Unauthorized response.
 */
function res403()
{
    header("HTTP/1.1 403 Unauthorized");
    echo "403 Unauthorized";
    exit;
}

/**
 * 指定されたデータとステータスコードでJSONレスポンスを送信します。
 * Sends a JSON response with the given data and status code.
 *
 * @param mixed $data The data to send in the response.
 * @param int $status The HTTP status code (default is 200).
 */
function resJson($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * アセットのURLを生成します。
 * Generates a URL for an asset.
 *
 * @param string $path The path to the asset.
 * @return string The full URL to the asset.
 */
function asset($path)
{
    global $BASE_URL;
    return "$BASE_URL/assets/$path";
}

/**
 * ログメッセージをファイルに書き込みます。
 * Writes a log message to a file.
 *
 * @param string $message The message to log.
 */
function trace($message)
{
    global $LOG_DIR;

    checkDir($LOG_DIR);
    $date = date('Ymd');
    $logFile = $LOG_DIR . "log_$date.txt";
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp]>> $message" . PHP_EOL;
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

/**
 * 指定されたディレクトリが存在しない場合は作成します。
 * Creates the specified directory if it does not exist.
 *
 * @param string $dir The directory path to check/create.
 */
function checkDir($dir)
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true); // recursive mkdir
    }
}