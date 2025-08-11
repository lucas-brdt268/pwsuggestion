<?php
require_once './include/config.php';

/**
 * 
 */

function trySuggestColor($imagePath, $style)
{
    global $OPENAI_API_KEY;

    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $imageData = file_get_contents($imagePath);
    $base64Image = base64_encode($imageData);
    $mimeType = mime_content_type($imagePath);

    $data = [
        // 'model' => 'gpt-3.5-turbo',
        'model' => 'gpt-4o',  // or 'gpt-3.5-turbo'

        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a professional house exterior wall painter.'
            ],
            [
                'role' => 'user',
                'content' => [
                    [
                        "type" => "text",
                        "text" => "You must provide me a suitable exterior wall color name of the house in the {$style} style."
                            . ' The color name format should be like: '
                            . '"a neutral, medium-dark gray with a hint of green".\n'
                            . ' Output style is "Color Name: [color name]" if succeed, "Failed: [reason]" if fail.'
                    ],
                    [
                        "type" => "image_url",
                        "image_url" => [
                            "url" => "data:$mimeType;base64,$base64Image",
                        ]
                    ]
                ]
            ]
        ],
        'temperature' => 0.7
    ];

    $headers = [
        'Authorization: Bearer ' . $OPENAI_API_KEY,
        'Content-Type: application/json'
    ];

    global $TIMEOUT, $__start_time;
    $elapsedTime = microtime(true) - $__start_time;
    $remainTimeout = $TIMEOUT - $elapsedTime;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_TIMEOUT, $remainTimeout); // タイムアウトを30秒に設定
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 接続タイムアウトを10秒に設定

    $response = curl_exec($ch);
    // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        throw new Exception('Curl error: ' . curl_error($ch));
    }
    curl_close($ch);
    $result = json_decode($response, true);
    $content =  $result['choices'][0]['message']['content'];
    if (str_starts_with($content, 'Failed')) {
        $reason = trim(str_replace('Failed: ', '', $content));
        throw new Exception("Failed to suggest ($reason)");
    }
    $colorName = trim(str_replace('Color Name: ', '', $content));
    return $colorName;
}
