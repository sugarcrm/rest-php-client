<?php

namespace Sugarcrm\REST\Endpoint\Traits;

trait ParseFilesTrait
{
    /**
     * Parse files array into standard format
     */
    protected function parseFiles(array $files): array
    {
        $parsed = [];
        foreach ($files as $file) {
            if (is_string($file)) {
                $filePath = $file;
                $fileName = basename($filePath);
            } elseif (is_array($file)) {
                $filePath = $file['path'];
                $fileName = $file['name'] ?? null;
            } elseif (is_object($file)) {
                $filePath = $file->path;
                $fileName = $file->name ?? null;
            }

            if (file_exists($filePath)) {
                $parsed[] = [
                    'path' => $filePath,
                    'name' => $fileName ?? basename($filePath),
                ];
            }
        }

        return $parsed;
    }
}
