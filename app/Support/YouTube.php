<?php

namespace App\Support;

/**
 * Video produk dari YouTube. Yang disimpan hanya ID video (11 karakter).
 */
class YouTube
{
    /**
     * Ambil ID dari link YouTube. Mendukung:
     * youtube.com/watch?v=ID, youtu.be/ID, youtube.com/shorts/ID, youtube.com/embed/ID
     */
    public static function idFromUrl(?string $url): ?string
    {
        $pattern = '~^(?:https?://)?(?:www\.|m\.)?(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|embed/)|youtu\.be/)([A-Za-z0-9_-]{11})(?:[?&#/].*)?$~';

        return preg_match($pattern, trim((string) $url), $match) ? $match[1] : null;
    }

    public static function watchUrl(string $id): string
    {
        return "https://www.youtube.com/watch?v={$id}";
    }

    public static function thumbnailUrl(string $id): string
    {
        return "https://i.ytimg.com/vi/{$id}/hqdefault.jpg";
    }

    // Domain nocookie: YouTube tidak memasang cookie pelacak sebelum video diputar
    public static function embedUrl(string $id): string
    {
        return "https://www.youtube-nocookie.com/embed/{$id}?autoplay=1&rel=0";
    }
}
