<?php

function e($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function upload_path($filename = '')
{
    return __DIR__ . '/../uploads/dokumen/' . $filename;
}

function payment_upload_path($filename = '')
{
    return __DIR__ . '/../uploads/pembayaran/' . $filename;
}

function rupiah($number)
{
    return 'Rp ' . number_format((float) $number, 0, ',', '.');
}
