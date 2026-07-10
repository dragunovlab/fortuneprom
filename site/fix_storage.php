<?php
/**
 * Скрипт для проверки и исправления директории storage и её поддиректорий в OpenCart 3.
 * Загрузите этот файл в корень сайта (httpdocs/fix_storage.php) и запустите через браузер:
 * https://fortuneprom.kz/fix_storage.php
 * После выполнения обязательно удалите скрипт!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Восстановление директорий OpenCart Storage</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.5; background: #f9f9f9; color: #333; }
        h1 { color: #2c3e50; }
        .log-container { background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .info { color: #3498db; font-weight: bold; }
        .success { color: #27ae60; font-weight: bold; }
        .warning { color: #e67e22; font-weight: bold; }
        .error { color: #c0392b; font-weight: bold; }
        .btn { display: inline-block; padding: 10px 15px; background: #c0392b; color: #fff; text-decoration: none; border-radius: 3px; margin-top: 20px; font-weight: bold; border: none; cursor: pointer; }
        .btn:hover { background: #a93226; }
    </style>
</head>
<body>
    <h1>Диагностика и восстановление прав на директорию Storage</h1>
    <div class='log-container'><pre>";

$storageBase = '/var/www/vhosts/fortuneprom.kz/storage';
$subDirs = [
    'cache',
    'download',
    'logs',
    'modification',
    'session',
    'upload'
];

echo "Базовый путь storage: <strong>" . htmlspecialchars($storageBase) . "</strong>\n\n";

// 1. Проверяем базовую директорию storage
if (!file_exists($storageBase)) {
    echo "<span class='warning'>[WARNING] Базовая директория storage не существует. Пытаемся создать...</span>\n";
    if (mkdir($storageBase, 0777, true)) {
        echo "<span class='success'>[SUCCESS] Базовая директория storage успешно создана.</span>\n";
        @chmod($storageBase, 0777);
    } else {
        echo "<span class='error'>[ERROR] Не удалось создать базовую директорию storage. Проверьте права родительской папки /var/www/vhosts/fortuneprom.kz/</span>\n";
    }
} else {
    echo "<span class='success'>[OK] Базовая директория storage существует.</span>\n";
    if (!is_writable($storageBase)) {
        echo "<span class='warning'>[WARNING] Базовая директория storage недоступна для записи. Пытаемся сменить права...</span>\n";
        if (@chmod($storageBase, 0777)) {
            echo "<span class='success'>[SUCCESS] Права на storage изменены на 0777.</span>\n";
        } else {
            echo "<span class='error'>[ERROR] Не удалось изменить права на storage.</span>\n";
        }
    } else {
        echo "<span class='success'>[OK] Базовая директория storage доступна для записи.</span>\n";
    }
}

// 2. Проверяем поддиректории
foreach ($subDirs as $subDir) {
    $fullPath = $storageBase . '/' . $subDir;
    echo "\nПроверка поддиректории: <strong>" . htmlspecialchars($subDir) . "</strong> (" . htmlspecialchars($fullPath) . ")\n";
    
    if (!file_exists($fullPath)) {
        echo "  - Директория отсутствует. Пытаемся создать...\n";
        if (mkdir($fullPath, 0777, true)) {
            echo "  - <span class='success'>[SUCCESS] Создана успешно.</span>\n";
            @chmod($fullPath, 0777);
        } else {
            echo "  - <span class='error'>[ERROR] Не удалось создать директорию.</span>\n";
        }
    } else {
        echo "  - Директория существует.\n";
        if (!is_writable($fullPath)) {
            echo "  - <span class='warning'>[WARNING] Недоступна для записи. Пытаемся исправить права...</span>\n";
            if (@chmod($fullPath, 0777)) {
                echo "  - <span class='success'>[SUCCESS] Права изменены на 0777.</span>\n";
            } else {
                echo "  - <span class='error'>[ERROR] Не удалось изменить права.</span>\n";
            }
        } else {
            echo "  - <span class='success'>[OK] Доступна для записи.</span>\n";
        }
    }
}

// 3. Тестируем запись логов
$logDir = $storageBase . '/logs';
if (is_dir($logDir) && is_writable($logDir)) {
    echo "\n<span class='info'>[INFO] Тестирование создания файлов логов в " . htmlspecialchars($logDir) . "...</span>\n";
    $testFiles = ['error.log', 'openbay.log'];
    foreach ($testFiles as $testFile) {
        $filePath = $logDir . '/' . $testFile;
        echo "Тест файла " . htmlspecialchars($testFile) . ": ";
        $handle = @fopen($filePath, 'a');
        if ($handle) {
            echo "<span class='success'>Успешно открыт на запись.</span> ";
            if (@fwrite($handle, date('Y-m-d H:i:s') . " - Test log write from fix_storage.php\n") !== false) {
                echo "<span class='success'>Запись прошла успешно.</span> ";
            } else {
                echo "<span class='error'>Ошибка записи.</span> ";
            }
            fclose($handle);
            @chmod($filePath, 0666);
        } else {
            echo "<span class='error'>Не удалось открыть файл на запись (fopen failed).</span>";
        }
        echo "\n";
    }
} else {
    echo "\n<span class='error'>[ERROR] Пропуск теста логов: директория logs отсутствует или недоступна для записи.</span>\n";
}

echo "\nСкрипт завершил работу.\n";
echo "</pre></div>";

// Кнопка удаления скрипта
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if (@unlink(__FILE__)) {
        echo "<p class='success'>Скрипт успешно удален с сервера для обеспечения безопасности!</p>";
        echo "<p><a href='/'>Перейти на главную страницу сайта</a></p>";
    } else {
        echo "<p class='error'>Не удалось автоматически удалить файл " . htmlspecialchars(basename(__FILE__)) . ". Пожалуйста, удалите его вручную через FTP/панель хостинга.</p>";
    }
} else {
    echo "<form method='GET' style='margin-top: 20px;'>
            <input type='hidden' name='action' value='delete'>
            <button type='submit' class='btn'>Удалить этот скрипт с сервера</button>
          </form>";
}

echo "</body>
</html>";
