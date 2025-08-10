<?php
require_once "./include/request_init.php";
require_once "./include/helpers.php";
require_once "./include/colorlist.php";

/**
 * index.php
 * 壁の色シミュレーションプレビューのメインページ
 * Main page for wall color simulation preview
 */

$colorList = colorlist();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>外壁カラーシミュレーション</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Begin: Styles -->
    <link rel="stylesheet" href="<?= asset('style.css') ?>">
    <!-- End: Styles -->
</head>

<body>
    <h1>外壁カラーシミュレーション</h1>

    <div class="block-cover" id="processingMessage">
        <span>画像生成中...</span>
        <button type="button" id="cancelButton" onclick="location.reload();">キャンセル</button>
    </div>

    <!-- Begin: Container -->
    <div class="container">

        <!-- Begin: Image upload form -->
        <form id="imageForm" method="post" action="paint.php">

            <!-- Begin: Image upload part -->
            <div class="form-group">
                <label for="imageUpload">塗り替えをする住宅の画像を選択してください</label>
                <div class="file-upload-wrapper">
                    <button type="button" id="fileUploadBtn">ファイルを選択</button>
                    <span id="fileName">画像ファイルをアップロードする</span>
                    <input type="file" id="imageUpload" accept="image/*" name="image" hidden>
                </div>
            </div>
            <!-- End: Image upload part -->

            <!-- Begin: Color select part -->
            <div class="form-group">
                <label>色を選択してください</label>
                <input type="text" id="colorSearch" placeholder="日塗工の塗料番号で絞り込み" oninput="filterColorList(this.value)" class="filter-input">
                <!-- Color name select -->
                <!-- <select id="colorName" name="color_name"> -->
                <!-- <option value="red">赤 (Red)</option> -->
                <!-- <option value="green">緑 (Green)</option> -->
                <!-- <option value="light green">薄緑 (Light Green)</option> -->
                <!-- <option value="blue">青 (Blue)</option> -->
                <!-- <option value="yellow">黄 (Yellow)</option> -->
                <!-- <option value="magenta">紫 (Magenta)</option> -->
                <!-- <option value="cyan">水色 (Cyan)</option> -->
                <!-- <option value="custom">カスタムカラー</option> -->
                <!-- </select> -->
                <!-- Custom color picker -->
                <!-- <input type="color" id="colorPicker" name="color_custom"> -->
                <!-- <input type="color" id="colorPicker" name="color_custom" value="" hidden> -->
                <div id="colorList" class="color-list">
                    <?php foreach ($colorList as $color): ?>
                        <div class="color-item-wrap">
                            <div class="color-item" style="background-color: <?= $color['hex'] ?>;"
                                onclick="selectColor('<?= $color['hex'] ?>', '<?= $color['name'] ?>');event.currentTarget.classList.add('selected');">
                                <?= $color['name'] ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <!-- End: Color select part -->

            <!-- Begin: Alert message -->
            <div id="alert" class="alert fade-in">
                <span id="alertText"></span>
                <button class="close-btn" type="button" onclick="closeAlert()">&times;</button>
            </div>
            <!-- End: Alert message -->

            <!-- Submit button -->
            <button type="submit" id="submitButton" class="btn">カラーシミュレーション開始</button>

        </form>
        <!-- End: Image upload form -->

        <!-- Begin: Preview area with original -->
        <div class="preview" id="previewArea">
            <img id="originalImage" src="" alt="元画像" style="display: none;">
            <img id="generatedImage" src="" alt="プレビュー画像" style="display: none;">
            <span id="usedColor" style="display: none;"></span>
        </div>
        <div id="processTime" style="display: none;">処理時間: 0s</div>
        <!-- End: Preview area with original -->

        <!-- Begin: Help buttons -->
        <div class="help-buttons" id="helpButtons" style="display: none;">
            <button class="btn fullscreen-btn" id="fullscreenBtn">全画面表示</button>
            <button class="btn download-btn" id="downloadBtn">ダウンロード</button>
        </div>
        <!-- End: Help buttons -->
    </div>
    <!-- End: Container -->

    <!-- Begin: Scripts -->
    <!-- <script type="text/javascript" src="https://chir.ag/projects/ntc/ntc.js"></script> -->
    <script>
        const ASSET_URL = '<?= asset('') ?>';
    </script>
    <script src="<?= asset('script.js') ?>"></script>
    <script>
        document.querySelector('#colorList .color-item:first-child').click();
    </script>
    <!-- End: Scripts -->
</body>

</html>