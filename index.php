<?php

use Random\RandomException;

session_start();

ini_set('memory_limit', '512M');
ini_set('max_execution_time', 120);


function getNamesStoragePath(): string {
    return __DIR__ . '/images/.filenames.json';
}

function loadOriginalNames(): array {
    $file = getNamesStoragePath();
    if (file_exists($file)) {
        $data = file_get_contents($file);
        $arr = json_decode($data, true);
        return is_array($arr) ? $arr : [];
    }
    return [];
}

function saveOriginalNames(array $names): void {
    $file = getNamesStoragePath();
    file_put_contents($file, json_encode($names, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function addOriginalName(string $generatedName, string $originalName): void {
    $names = loadOriginalNames();
    $names[$generatedName] = $originalName;
    saveOriginalNames($names);
}

function writeLog(): void {
    $logDir  = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logFile = $logDir . '/log.txt';

    $entry = date('Y-m-d H:i:s') . ' | IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . ' | URI: ' . ($_SERVER['REQUEST_URI'] ?? 'unknown');

    $existing = [];
    if (file_exists($logFile)) {
        $data = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $existing = is_array($data) ? $data : [];
        if (count($existing) >= 10) {
            $files  = glob($logDir . '/log*.txt');
            $maxNum = 0;
            foreach ($files as $file) {
                if (preg_match('/log(\d+)\.txt$/', $file, $m)) {
                    $maxNum = max($maxNum, (int)$m[1]);
                }
            }
            rename($logFile, $logDir . '/log' . ($maxNum + 1) . '.txt');
            $existing = [];
        }
    }

    $existing[] = $entry;
    file_put_contents($logFile, implode(PHP_EOL, $existing) . PHP_EOL);
}

writeLog();


$imagesDir = __DIR__ . '/images';
$thumbsDir = __DIR__ . '/thumbs';

if (!is_dir($imagesDir)) mkdir($imagesDir, 0755, true);
if (!is_dir($thumbsDir)) mkdir($thumbsDir, 0755, true);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
    header('Content-Type: application/json');
    $filename = basename($_POST['delete_image']);
    $origPath = $imagesDir . '/' . $filename;
    $thumbPath = $thumbsDir . '/' . $filename;
    $deleted = false;
    if (file_exists($origPath) && unlink($origPath)) {
        if (file_exists($thumbPath)) unlink($thumbPath);
        $names = loadOriginalNames();
        if (isset($names[$filename])) {
            unset($names[$filename]);
            saveOriginalNames($names);
        }
        $deleted = true;
    }
    echo json_encode(['success' => $deleted]);
    exit;
}

function makeThumbnail(string $srcPath, string $destPath, int $thumbWidth = 300): bool {
    $info = getimagesize($srcPath);
    if (!$info || !$info[0] || !$info[1]) return false;

    list($width, $height, $type) = $info;

    $maxPixels = 8000;
    if ($width > $maxPixels || $height > $maxPixels) {
        return false;
    }

    $ratio       = $thumbWidth / $width;
    $thumbHeight = (int)($height * $ratio);

    switch ($type) {
        case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($srcPath); break;
        case IMAGETYPE_PNG:  $src = imagecreatefrompng($srcPath);  break;
        case IMAGETYPE_GIF:  $src = imagecreatefromgif($srcPath);  break;
        default: return false;
    }

    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

    if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
        imagefilledrectangle($thumb, 0, 0, $thumbWidth, $thumbHeight, $transparent);
    }

    imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

    $success = match ($type) {
        IMAGETYPE_JPEG => imagejpeg($thumb, $destPath, 85),
        IMAGETYPE_PNG => imagepng($thumb, $destPath, 8),
        IMAGETYPE_GIF => imagegif($thumb, $destPath),
        default => false,
    };

    imagedestroy($src);
    imagedestroy($thumb);
    return $success;
}

function buildGallery(string $imagesDirPath, string $thumbsDirPath): string {
    $baseDir    = __DIR__;
    $originalNames = loadOriginalNames();

    $imagesUrl = str_replace('\\', '/', str_replace($baseDir, '.', $imagesDirPath));
    $thumbsUrl = str_replace('\\', '/', str_replace($baseDir, '.', $thumbsDirPath));

    if (!is_dir($imagesDirPath)) {
        return '<p class="empty-msg">Папка с изображениями не найдена.</p>';
    }

    $files = array_filter(scandir($imagesDirPath), function ($file) use ($imagesDirPath) {
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if ($file[0] === '.') return false;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        return in_array($ext, $extensions) && is_file($imagesDirPath . '/' . $file);
    });

    if (empty($files)) {
        return '<p class="empty-msg">Пока нет загруженных изображений. Загрузите первое!</p>';
    }

    $html = '<div class="gallery" id="gallery">';
    foreach ($files as $file) {
        $origFull  = $imagesDirPath . '/' . $file;
        $thumbFull = $thumbsDirPath . '/' . $file;

        if (!file_exists($thumbFull)) {
            makeThumbnail($origFull, $thumbFull);
        }

        $origUrl  = $imagesUrl . '/' . rawurlencode($file);
        $thumbUrl = $thumbsUrl . '/' . rawurlencode($file);

        $displayName = $originalNames[$file] ?? $file;
        $displayName = mb_strimwidth($displayName, 0, 30, '…');

        $html .= sprintf(
            '<div class="gallery-item" data-filename="%s">
                <button class="delete-btn" data-file="%s">×</button>
                <a href="%s" target="_blank" title="%s">
                    <img src="%s" alt="%s" loading="lazy">
                </a>
                <div class="gallery-caption">%s</div>
            </div>',
            htmlspecialchars($file),
            htmlspecialchars($file),
            htmlspecialchars($origUrl),
            htmlspecialchars($file),
            htmlspecialchars($thumbUrl),
            htmlspecialchars($file),
            htmlspecialchars($displayName)
        );
    }
    $html .= '</div>';
    return $html;
}

$uploadMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file         = $_FILES['image'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize      = 5 * 1024 * 1024; // 5 МБ
    $error        = '';

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Ошибка при загрузке файла. Код: ' . $file['error'];
    } elseif (!in_array($file['type'], $allowedTypes)) {
        $error = 'Разрешены только форматы: JPEG, PNG и GIF.';
    } elseif ($file['size'] > $maxSize) {
        $error = 'Размер файла не должен превышать 5 МБ.';
    } else {
        $imgInfo = @getimagesize($file['tmp_name']);
        if (!$imgInfo) {
            $error = 'Загруженный файл не является изображением.';
        } else {
            list($width, $height) = $imgInfo;
            if ($width > 8000 || $height > 8000) {
                $error = 'Изображение слишком большое (максимум 8000×8000 пикселей). Пожалуйста, уменьшите его перед загрузкой.';
            }
        }
    }

    if (!$error) {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        try {
            $newName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
        } catch (RandomException $e) {

        }

        $origPath  = $imagesDir . '/' . $newName;
        $thumbPath = $thumbsDir . '/' . $newName;

        if (move_uploaded_file($file['tmp_name'], $origPath)) {
            if (makeThumbnail($origPath, $thumbPath)) {
                addOriginalName($newName, $file['name']);
                $_SESSION['flash_message'] = '<div class="msg success">Изображение успешно добавлено в галерею!</div>';
            } else {
                unlink($origPath);
                $_SESSION['flash_message'] = '<div class="msg error">Не удалось создать миниатюру (возможно, слишком большое изображение).</div>';
            }
        } else {
            $_SESSION['flash_message'] = '<div class="msg error">Не удалось сохранить файл.</div>';
        }
    } else {
        $_SESSION['flash_message'] = '<div class="msg error">' . htmlspecialchars($error) . '</div>';
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_SESSION['flash_message'])) {
    $uploadMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

$galleryHtml = buildGallery($imagesDir, $thumbsDir);

$photoCount = 0;
if (is_dir($imagesDir)) {
    $photoCount = count(array_filter(scandir($imagesDir), function ($f) use ($imagesDir) {
        return $f[0] !== '.' && is_file($imagesDir . '/' . $f);
    }));
}

$templateFile = __DIR__ . '/gallery.html';
if (!file_exists($templateFile)) {
    die('Шаблон gallery.html не найден.');
}

$template = file_get_contents($templateFile);
$template = str_replace('{{galleryHtml}}', $galleryHtml, $template);
$template = str_replace('{{photoCount}}', $photoCount, $template);
$template = str_replace('{{uploadMessage}}', $uploadMessage, $template);

echo $template;