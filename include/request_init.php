<?php

/**
 * このファイルには、初期化処理が含まれています。
 * This file contains initialization processes.
 */

// エラーレポートを無効にする
// Disable error reporting
// error_reporting(0);
// ini_set('display_errors', '0');

// スクリプトの実行時間を60秒に設定
// Set script execution time to 60 seconds from now
// ini_set('max_execution_time', 60);
set_time_limit(60); // gives the script 60 seconds from this point

//
$__start_time = microtime(true); // スクリプトの開始時間を記録, Record the start time of the script