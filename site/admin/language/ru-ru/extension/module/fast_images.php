<?php
// Heading
$_['heading_title']    = 'Отложенная загрузка и Webp';

// Text
$_['text_extension']   = 'Расширения';
$_['text_success']     = 'Успех: вы модифицировали модуль!';
$_['text_edit']        = 'Изменить модуль';

// Entry
$_['entry_status']     = 'Статус';

$_['patch_success'] = 'Файл .htaccess успешно изменен.';
$_['unpatch_success'] = 'Файл .htaccess восстановлен.';

$_['settings_label'] = 'Настройки';
$_['entry_lazy'] = 'Отложенная загрузка изображений';
$_['entry_towebp'] = 'Изменения названий файлов на *.webp';
$_['entry_pregen'] = 'Генерация webp-файлов при генерации страниц.';
$_['entry_quality'] = 'WebP качество';

$_['gd_label'] = 'WEBP ПОДДЕРЖКА';
$_['gd_success'] = '<i class="fa fa-check-circle text-success"></i> Отлично, ваш сервер поддерживает формат webp.';
$_['gd_failed'] = '<i class="fa fa-exclamation-circle text-danger"></i> Предупреждение, этот сервер не поддерживает формат webp. Модуль нельзя использовать.';

$_['patch_label'] = 'Принудительный WEBP';
$_['forced_descr'] = '<b>Это дополнительная функция. </b><br />Включает автоматический генератор WEBP для всех файлов JPEG и PNG, включая изображения внутри CSS и в панели администратора.';
$_['forced_false'] = '<i class="fa fa-exclamation-circle text-danger"></i> К сожалению, ваш веб-сервер не распознается, эта функция недоступна. <br />Apache поддерживается автоматически, <br />Nginx с ручной настройкой.';
$_['forced_nginx'] = 'Чтобы включить функцию Forced WebP, необходимо добавить эти строки в конфигурацию Nginx в раздел вашего веб-сайта прямо в строке "location / {" или обратится в поддерку хостинга';

$_['patch_descr'] = '<i class="fa fa-exclamation-circle text-danger"></i> Для включения автогенератора WEBP необходимо добавить эти строки в файл .htaccess прямо под строкой <br /> RewriteEngine On';
$_['patched_descr'] = '<i class="fa fa-check-circle text-success"></i> Файл .htaccess изменен.';
$_['patch_alert'] = 'Внимание! Это может привести к ошибке на веб-сайте.';
$_['patch_button'] = 'Патч .htaccess';
$_['unpatch_button'] = 'Восстановить .htaccess';
$_['res_descr'] = 'Если вы видите «JPEG конвертированы» и «PNG конвертированы», значит, изменение выполнено успешно.';

$_['tab_general'] = 'Общие';
$_['tab_lazy'] = 'Отложенная загрузка';
$_['tab_webp'] = 'WebP';

// Error
$_['error_permission'] = 'Предупреждение: у вас нет разрешения на изменение этого модуля!';
$_['error_patching_finish'] = 'Предупреждение: файл .htaccess поврежден. Не может найден %s. Надо исправить вручную!';
$_['error_patching'] = 'Предупреждение: не удалось внести изменение в файл .htaccess.';
$_['error_unpatching'] = 'Предупреждение: Восстановление .htaccess. не удалось!';