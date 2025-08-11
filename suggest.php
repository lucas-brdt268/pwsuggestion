<?php
require_once './include/request_init.php';
require_once './include/helpers.php';
require_once './include/aisuggest.php';
require_once './include/imggen.php';

/**
 * suggest.php
 */

onlyPost();

trace("Start");

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    trace('Error(500): Image upload failed');
    resJson(['error' => '画像のアップロードに失敗しました'], 500);
}
$tempName = $_FILES['image']['tmp_name'];
$mimeType = $_FILES['image']['type'];
$fileName = basename($_FILES['image']['name']);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$fileSizeKB = $_FILES['image']['size'] / 1024; // サイズ（KB), Size in KB
$fileId = uniqid('img_');
$targetName = $UPLOAD_DIR . $fileId;
$targetPath = "$targetName.$fileType";

$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array($mimeType, $allowedTypes)) {
    trace('Error(500): Invalid image type');
    resJson(['error' => 'エラー: JPEG、PNG、またはWebP形式の画像をアップロードしてください。'], 500);
}

if ($fileSizeKB > 5120) { // 5MBまでに制限, Limit to 5MB
    trace('Error(500): Image size exceeds the limit of 5MB');
    resJson(['error' => '画像サイズが5MBの制限を超えています'], 500);
}

checkDir($UPLOAD_DIR);
if (!move_uploaded_file($tempName, $targetPath)) {
    trace('Error(500): Failed to save uploaded image');
    resJson(['error' => 'アップロードした画像を保存できませんでした'], 500);
}
trace("File id: $fileId");

$style = $_POST['style'];
trace("Style: $style");

try {
    $colorName = trySuggestColor($targetPath, $style);
} catch (Exception $e) {
    trace('Error(500): ' . $e->getMessage());
    resJson(['error' => 'システムに問題が発生しています。しばらく経ってから再度お試しください。'], 500);
}
trace("Color Name: $colorName");

try {
    $imgUrl = imggen($targetPath, $colorName);
    trace("Generated Image URL: $imgUrl");
} catch (Exception $e) {
    trace('Error(500): ' . $e->getMessage());
    resJson(['error' => 'システムに問題が発生しています。しばらく経ってから再度お試しください。'], 500);
}

$imageData = file_get_contents($imgUrl);
$base64Image = base64_encode($imageData);
resJson(['base64_image' => $base64Image, 'suggested_color' => $colorName]);
