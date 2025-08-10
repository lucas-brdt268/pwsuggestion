<?php
require_once "./include/request_init.php";
require_once "./include/helpers.php";

/**
 * index.php
 * 壁の色シミュレーションプレビューのメインページ
 * Main page for wall color simulation preview
 */
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>家の外壁色提案</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Begin: Styles -->
    <link rel="stylesheet" href="<?= asset('style.css') ?>">
    <!-- End: Styles -->
</head>

<body>
    <div class="container">
        <h1>家の外壁色提案</h1>
        <div class="input-group">
            <label for="imageUpload">家の画像をアップロード</label>
            <label class="custom-file-upload">画像を選択</label>
            <input type="file" id="imageUpload" accept="image/*">
            <img id="imagePreview" alt="アップロードされた画像">
        </div>
        <div class="input-group">
            <label for="styleSelect">スタイルを選択</label>
            <select id="styleSelect">
                <option value="modern">モダン</option>
                <option value="traditional">和風</option>
                <option value="coastal">海辺風</option>
                <option value="rustic">田舎風</option>
            </select>
        </div>
        <div class="input-group">
            <label for="methodSelect">提案方法を選択</label>
            <select id="methodSelect">
                <option value="ai">AI分析</option>
                <option value="color-theory">色彩理論</option>
                <option value="trend-based">トレンドベース</option>
            </select>
        </div>
        <button id="suggestButton">色を提案</button>
        <div id="suggestionOutput">提案された色: なし</div>
        <img id="preview" alt="提案色プレビュー">
    </div>
    
    <!-- Begin: Scripts -->
    <script>
        const ASSET_URL = '<?= asset('') ?>';
    </script>
    <script src="<?= asset('script.js') ?>"></script>
    <!-- End: Scripts -->
</body>

</html>