<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ImageProxyController extends Controller
{
    /**
     * Streams images from S3/MinIO securely to bypass Mixed Content blocks.
     */
    public function show($filename)
    {
        $path = 'questions/' . $filename;
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';

        if (!Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        $content = Storage::disk($disk)->get($path);
        $mime = Storage::disk($disk)->mimeType($path) ?: 'image/jpeg';

        return Response::make($content, 200, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
