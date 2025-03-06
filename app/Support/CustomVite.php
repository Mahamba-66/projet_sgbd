<?php

namespace App\Support;

use Illuminate\Foundation\Vite as BaseVite;
use Illuminate\Support\HtmlString;

class CustomVite extends BaseVite
{
    protected function chunk($manifest, $file)
    {
        // Si le fichier est app.css, on le remplace par le bon chemin
        if ($file === 'resources/css/app.css') {
            return [
                'file' => 'assets/app-DM65pi2Y.css',
                'src' => 'resources/css/app.css',
                'css' => []
            ];
        }

        return parent::chunk($manifest, $file);
    }

    public function __invoke($entrypoints = '')
    {
        $entrypoints = is_string($entrypoints)
            ? [$entrypoints]
            : $entrypoints;

        $this->withEntryPoints($entrypoints);

        $tags = collect($entrypoints)
            ->map(fn ($entrypoint) => $this->makeTag($entrypoint))
            ->filter()
            ->join('');

        return new HtmlString($tags);
    }
}
