<?php

namespace MakelarisJR\Laravel2FA\Actions;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class GenerateGoogleQRCode
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generate(string $name = 'Laravel', int $size = 200): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $name = urlencode($name);
        $data = $writer->writeString("otpauth://totp/${name}?secret={$this->secret}", 'utf-8');

        return 'data:image/svg+xml;base64,' . base64_encode($data);
    }
}
