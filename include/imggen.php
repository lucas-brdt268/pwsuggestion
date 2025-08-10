<?php
require_once "./include/config.php";

/**
 * このファイルには、Replicate API を使用して画像を生成する関数が含まれています。
 * 家の画像の外壁の色を変更できます。
 * This file contains functions to generate images using the Replicate API.
 * It allows modification of the exterior wall color of a house image.
 */

/**
 * Replicate API を使用して、外壁の色を変更した画像を生成します。
 * Generates an image with a modified exterior wall color using the Replicate API.
 *
 * @param string $imagePath Path to the input image.
 * @param string $color The color to modify the exterior wall to.
 * @return string URL of the generated image.
 */
function imggen($imagePath, $color)
{
    //
    global $REPLICATE_API_KEY;

    // 入力画像を準備する  
    // Prepare the input image
    $imageBase64 = base64_encode(file_get_contents($imagePath));
    $inputImage = "data:application/octet-stream;base64,$imageBase64";

    // Replicate API への CURL リクエスト
    // CURL Request to Replicate API
    $apiKey = $REPLICATE_API_KEY;
    $prompt = "Paint the exterior wall of the house lightly in $color.";

    $data = [
        'input' => [
            'input_image' => $inputImage,
            'prompt' => $prompt,
            'aspect_ratio' => 'match_input_image',
            'output_format' => 'jpg',
            'prompt_upsampling' => false,
        ]
    ];

    global $TIMEOUT, $__start_time;
    $remainTimeout = $TIMEOUT - $__start_time;

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.replicate.com/v1/models/black-forest-labs/flux-kontext-pro/predictions',
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => $remainTimeout,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    // CURL エラーハンドリング
    // CURL error handling
    if ($err) {
        throw new Exception("cURL Error: $err");
    }

    // レスポンスをパース
    // No CURL error, parse the response
    $result = json_decode($response, true);
    $outputUrl = pollRequestLoop($result['id']);

    //
    return $outputUrl;
}

/**
 * Replicate API からの予測結果をポーリングして、生成された画像の URL を取得します。
 * Polls the Replicate API for the prediction result and retrieves the generated image URL.
 *
 * @param string $predictionId The ID of the prediction to poll.
 * @return string|null The URL of the generated image or null on failure.
 */
function pollRequestLoop($predictionId)
{
    do {
        sleep(2); // 2秒待機, Wait 2 seconds
        $result = pollRequestOnce($predictionId);
        if ($result['status'] === 'succeeded') {
            return $result['output'];
        } elseif ($result['status'] === 'failed') {
            throw new Exception("Prediction failed: {$result['error']}");
        }
    } while ($result['status'] !== 'succeeded' && $result['status'] !== 'failed');
}

/** 
 * Replicate API からの予測結果を一度だけポーリングします。
 * Polls the Replicate API for the prediction result once.
 *
 * @param string $predictionId The ID of the prediction to poll.
 * @return array The prediction result.
 */
function pollRequestOnce($predictionId)
{
    global $REPLICATE_API_KEY;

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.replicate.com/v1/predictions/$predictionId",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $REPLICATE_API_KEY",
            "Content-Type: application/json",
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        throw new Exception("cURL Error: $err");
    }

    return json_decode($response, true);
}
