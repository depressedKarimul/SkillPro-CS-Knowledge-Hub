<?php

if (!function_exists('format_profile_pic_path')) {
    function format_profile_pic_path(?string $pic, string $default = 'https://i.pravatar.cc/150'): string
    {
        if (!$pic) {
            return $default;
        }

        if (preg_match('#^https?://#i', $pic)) {
            return $pic;
        }

        $normalized = str_replace('\\', '/', trim($pic));
        $normalized = preg_replace('#^(\.\./)+#', '', $normalized);

        $knownRoots = ['images/', 'uploads/', 'profile/', 'photos/'];
        foreach ($knownRoots as $root) {
            $pos = stripos($normalized, $root);
            if ($pos !== false) {
                $normalized = substr($normalized, $pos);
                break;
            }
        }

        if (preg_match('#^[A-Za-z]:/#', $normalized)) {
            $normalized = basename($normalized);
        }

        $normalized = ltrim($normalized, '/');

        if ($normalized === '') {
            return $default;
        }

        if (strpos($normalized, '/') === false) {
            $normalized = 'Images/' . $normalized;
        }

        return '../' . ltrim($normalized, '/');
    }
}

