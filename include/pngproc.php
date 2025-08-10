<?php

function convertToPngRgba($inputPath, $outputPath)
{
    // Get image info
    $info = getimagesize($inputPath);
    if (!$info) {
        die("Invalid image file.");
    }

    $mime = $info['mime'];

    // Load the image based on MIME type
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($inputPath);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($inputPath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($inputPath);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($inputPath);
            break;
        default:
            die("Unsupported image format: $mime");
    }

    // Get original width and height
    $width = imagesx($image);
    $height = imagesy($image);

    // Create true color image with alpha
    $rgba = imagecreatetruecolor($width, $height);
    imagealphablending($rgba, false); // disable blending
    imagesavealpha($rgba, true);      // save full alpha channel

    // Fill transparent background
    $transparent = imagecolorallocatealpha($rgba, 0, 0, 0, 127);
    imagefill($rgba, 0, 0, $transparent);

    // Copy original image into RGBA
    imagecopy($rgba, $image, 0, 0, 0, 0, $width, $height);

    // Save to PNG
    imagepng($rgba, $outputPath);

    // Clean up
    imagedestroy($image);
    imagedestroy($rgba);

    // echo "Converted to PNG RGBA: $outputPath\n";
}

function isPngRgba($filePath)
{
    // Load image
    $img = imagecreatefrompng($filePath);
    if (!$img) {
        return false;
    }

    // If imagesavealpha was used, alpha blending must be off
    imagealphablending($img, false);

    $width = imagesx($img);
    $height = imagesy($img);

    // Loop through pixels (sample up to 100 pixels for speed)
    $samples = 0;
    for ($y = 0; $y < $height; $y += max(1, (int)($height / 10))) {
        for ($x = 0; $x < $width; $x += max(1, (int)($width / 10))) {
            $rgba = imagecolorat($img, $x, $y);
            $alpha = ($rgba & 0x7F000000) >> 24;
            if ($alpha > 0) {
                // Found at least one partially transparent pixel
                imagedestroy($img);
                return true; // RGBA
            }
            $samples++;
            if ($samples > 100) break 2;
        }
    }

    imagedestroy($img);
    return false; // Opaque (RGB only)
}