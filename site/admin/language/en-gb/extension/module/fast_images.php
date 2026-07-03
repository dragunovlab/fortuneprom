<?php
// Heading
$_['heading_title']    = ' Lazyload & Webp';

// Text
$_['text_extension']   = 'Extensions';
$_['text_success']     = 'Success: You have modified module!';
$_['text_edit']        = 'Edit Module';

// Entry
$_['entry_status']     = 'Status';

$_['patch_success'] = 'File .htaccess is patched successfully.';
$_['unpatch_success'] = 'File .htaccess is restored.';

$_['settings_label'] = 'Settings';
$_['entry_lazy'] = 'Lazy load';
$_['entry_towebp'] = 'Change filenames to *.webp';
$_['entry_pregen'] = 'Generate webp files at page generation.';
$_['entry_quality'] = 'WebP quality';

$_['gd_label'] = 'WEBP SUPPORT';
$_['gd_success'] = '<i class="fa fa-check-circle text-success"></i> Great, this server support webp format.';
$_['gd_failed'] = '<i class="fa fa-exclamation-circle text-danger"></i> Warning, this server is not support webp format. Extension is not usable.';

$_['patch_label'] = 'Forced WEBP';
$_['forced_descr'] = '<b>This is ADDITIONAL feature. </b><br />Will enable WEBP auto generator for every JPEG ang PNG files including images inside CSS and in admin\'s zone.';
$_['forced_false'] = '<i class="fa fa-exclamation-circle text-danger"></i> Unortunately your werserver is not recognized, this feature is not available. <br />Apache supported automatically, <br />Nginx with manual configuration.';
$_['forced_nginx'] = 'To enable feature of Forced WebP needs to add this lines into Nginx config to the section of your website right down the line "location / {"';

$_['patch_descr'] = '<i class="fa fa-exclamation-circle text-danger"></i> To enable WEBP auto generator needs to add these strings to the file .htaccess right below the line <br /> RewriteEngine On';
$_['patched_descr'] = '<i class="fa fa-check-circle text-success"></i> File .htaccess is patched.';
$_['patch_alert'] = 'Attention! This can breakdown the website.';
$_['patch_button'] = 'Patch .htaccess';
$_['unpatch_button'] = 'Restore .htaccess';
$_['res_descr'] = 'If you see "JPEG converted" and "PNG converted" then patched successfully.';

$_['tech_label'] = 'AUTHOR';

$_['tab_general'] = 'Main';
$_['tab_lazy'] = 'Lazy load';
$_['tab_webp'] = 'WebP';

// Error
$_['error_permission'] = 'Warning: You do not have permission to modify this module!';
$_['error_patching_finish'] = 'Warning: File .htaccess is broken. Can\'t find %s. Should fix it manually!';
$_['error_patching'] = 'Warning: Patching of .htaccess is failed.';
$_['error_unpatching'] = 'Warning: Restoration of .htaccess. is failed!';