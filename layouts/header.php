<?php
$page_title = $page_title ?? 'Dashboard PMB';
$active_menu = $active_menu ?? '';
$app_base = $app_base ?? '..';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $app_base; ?>/assets/css/style.css">
</head>
<body>
<div class="app-shell">
