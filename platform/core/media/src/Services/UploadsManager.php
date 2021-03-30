<?php

namespace Botble\Media\Services;

use Carbon\Carbon;
use File;
use Mimey\MimeTypes;
use Storage;

class UploadsManager
{
    /**
     * @var MimeTypes
     */
    protected $mimeType;

    /**
     * UploadsManager constructor.
     * @param MimeTypes $mimeType
     */
    public function __construct(MimeTypes $mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * Return an array of file details for a file
     *
     * @param string $path
     * @return array
     */
    public function fileDetails($path)
    {
        return [
            'filename'  => File::basename($path),
            'url'       => $path,
            'mime_type' => $this->fileMimeType($path),
            'size'      => $this->fileSize($path),
            'modified'  => $this->fileModified($path),
        ];
    }

    /**
     * Return the mime type
     *
     * @param string $path
     * @return mixed|null|string
     */
    public function fileMimeType($path): ?string
    {
        return $this->mimeType->getMimeType(File::extension(Storage::path($path)));
    }

    /**
     * Return the file size
     *
     * @param string $path
     * @return int
     */
    public function fileSize($path)
    {
        return Storage::size($path);
    }

    /**
     * Return the last modified time
     *
     * @param string $path
     * @return string
     */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp(Storage::lastModified($path));
    }

    /**
     * @param string $folder
     * @return array|bool|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function createDirectory($folder)
    {
        $folder = $this->cleanFolder($folder);

        if (Storage::exists($folder)) {
            return trans('core/media::media.folder_exists', compact('folder'));
        }

        return Storage::makeDirectory($folder);
    }

    /**
     * Sanitize the folder name
     *
     * @param string $folder
     * @return string
     */
    protected function cleanFolder($folder)
    {
        return DIRECTORY_SEPARATOR . trim(str_replace('..', '', $folder), DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $folder
     * @return array|bool|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function deleteDirectory($folder)
    {
        $folder = $this->cleanFolder($folder);

        $filesFolders = array_merge(
            Storage::directories($folder),
            Storage::files($folder)
        );
        if (!empty($filesFolders)) {
            return trans('core/media::media.directory_must_empty');
        }

        return Storage::deleteDirectory($folder);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deleteFile($path)
    {
        $path = $this->cleanFolder($path);

        return Storage::delete($path);
    }

    /**
     * @param string $path
     * @param string $content
     * @return bool
     */
    public function saveFile($path, $content)
    {
        $path = $this->cleanFolder($path);

        return Storage::put($path, $content);
    }
}
