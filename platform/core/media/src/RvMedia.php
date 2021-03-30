<?php

namespace Botble\Media;

use Botble\Media\Http\Resources\FileResource;
use Botble\Media\Models\MediaFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Botble\Media\Services\UploadsManager;
use Botble\Media\Services\ThumbnailService;
use Exception;
use File;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Image;
use Storage;
use Throwable;
use Validator;

class RvMedia
{

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var UploadsManager
     */
    protected $uploadManager;

    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;

    /**
     * @var MediaFolderInterface
     */
    protected $folderRepository;

    /**
     * @var ThumbnailService
     */
    protected $thumbnailService;

    /**
     * @param MediaFileInterface $fileRepository
     * @param MediaFolderInterface $folderRepository
     * @param UploadsManager $uploadManager
     * @param ThumbnailService $thumbnailService
     */
    public function __construct(
        MediaFileInterface $fileRepository,
        MediaFolderInterface $folderRepository,
        UploadsManager $uploadManager,
        ThumbnailService $thumbnailService
    ) {
        $this->fileRepository = $fileRepository;
        $this->folderRepository = $folderRepository;
        $this->uploadManager = $uploadManager;
        $this->thumbnailService = $thumbnailService;

        $this->permissions = config('core.media.media.permissions', []);
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function renderHeader(): string
    {
        $urls = $this->getUrls();

        return view('core/media::header', compact('urls'))->render();
    }

    /**
     * Get all URLs
     * @return array
     */
    public function getUrls(): array
    {
        return [
            'base_url'                 => url(''),
            'base'                     => route('media.index'),
            'get_media'                => route('media.list'),
            'create_folder'            => route('media.folders.create'),
            'popup'                    => route('media.popup'),
            'download'                 => route('media.download'),
            'upload_file'              => route('media.files.upload'),
            'get_breadcrumbs'          => route('media.breadcrumbs'),
            'global_actions'           => route('media.global_actions'),
            'media_upload_from_editor' => route('media.files.upload.from.editor'),
        ];
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function renderFooter(): string
    {
        return view('core/media::footer')->render();
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function renderContent(): string
    {
        return view('core/media::content')->render();
    }

    /**
     * @param string|array $data
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data, $message = null): JsonResponse
    {
        return response()->json([
            'error'   => false,
            'data'    => $data,
            'message' => $message,
        ]);
    }

    /**
     * @param string $message
     * @param array $data
     * @param null $code
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($message, $data = [], $code = null, $status = 200): JsonResponse
    {
        return response()->json([
            'error'   => true,
            'message' => $message,
            'data'    => $data,
            'code'    => $code,
        ], $status);
    }

    /**
     * @param string $url
     * @return array|mixed
     */
    public function getAllImageSizes($url): array
    {
        $images = [];
        foreach ($this->getSizes() as $size) {
            $readableSize = explode('x', $size);
            $images = get_image_url($url, $readableSize);
        }

        return $images;
    }

    /**
     * @return array
     */
    public function getSizes(): array
    {
        return config('core.media.media.sizes', []);
    }

    /**
     * @param \Illuminate\Http\UploadedFile $fileUpload
     * @param int $folderId
     * @param string $folderSlug
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function handleUpload($fileUpload, $folderId = 0, $folderSlug = null): array
    {
        if (!$fileUpload) {
            return [
                'error'   => true,
                'message' => trans('core/media::media.can_not_detect_file_type'),
            ];
        }

        request()->merge(['uploaded_file' => $fileUpload]);

        $validator = Validator::make(request()->all(), [
            'uploaded_file' => 'required|mimes:' . config('core.media.media.allowed_mime_types'),
        ]);

        if ($validator->fails()) {
            return [
                'error'   => true,
                'message' => $validator->getMessageBag()->first(),
            ];
        }

        try {
            $file = $this->fileRepository->getModel();

            $maxSize = $this->getServerConfigMaxUploadFileSize();

            if ($fileUpload->getSize() / 1024 > (int)$maxSize) {
                return [
                    'error'   => true,
                    'message' => trans('core/media::media.file_too_big', ['size' => human_file_size($maxSize)]),
                ];
            }

            $fileExtension = $fileUpload->getClientOriginalExtension();

            if (!in_array($fileExtension, explode(',', config('core.media.media.allowed_mime_types')))) {
                return [
                    'error'   => true,
                    'message' => trans('core/media::media.can_not_detect_file_type'),
                ];
            }

            if ($folderId == 0 && !empty($folderSlug)) {
                $folder = $this->folderRepository->getFirstBy(['media_folders.slug' => $folderSlug]);

                if (!$folder) {
                    $folder = $this->folderRepository->createOrUpdate([
                        'user_id'   => Auth::check() ? Auth::user()->getKey() : 0,
                        'name'      => $this->folderRepository->createName($folderSlug, 0),
                        'slug'      => $this->folderRepository->createSlug($folderSlug, 0),
                        'parent_id' => 0,
                    ]);
                }

                $folderId = $folder->id;
            }

            $file->name = $this->fileRepository->createName(File::name($fileUpload->getClientOriginalName()),
                $folderId);

            $folderPath = $this->folderRepository->getFullPath($folderId);

            $fileName = $this->fileRepository->createSlug($file->name, $fileExtension, Storage::path($folderPath));

            $filePath = $fileName;

            if ($folderPath) {
                $filePath = $folderPath . '/' . $filePath;
            }

            $content = File::get($fileUpload->getRealPath());

            $this->uploadManager->saveFile($filePath, $content);

            $data = $this->uploadManager->fileDetails($filePath);

            if (empty($data['mime_type'])) {
                return [
                    'error'   => true,
                    'message' => trans('core/media::media.can_not_detect_file_type'),
                ];
            }

            $file->url = $data['url'];
            $file->size = $data['size'];
            $file->mime_type = $data['mime_type'];
            $file->folder_id = $folderId;
            $file->user_id = Auth::check() ? Auth::user()->getKey() : 0;
            $file->options = request()->input('options', []);
            $file = $this->fileRepository->createOrUpdate($file);

            $this->generateThumbnails($file);

            return [
                'error' => false,
                'data'  => new FileResource($file),
            ];
        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * Returns a file size limit in bytes based on the PHP upload_max_filesize and post_max_size
     * @return float|int
     */
    public function getServerConfigMaxUploadFileSize()
    {
        // Start with post_max_size.
        $maxSize = $this->parseSize(ini_get('post_max_size'));

        // If upload_max_size is less, then reduce. Except if upload_max_size is
        // zero, which indicates no limit.
        $uploadMax = $this->parseSize(ini_get('upload_max_filesize'));
        if ($uploadMax > 0 && $uploadMax < $maxSize) {
            $maxSize = $uploadMax;
        }

        return $maxSize;
    }

    /**
     * @param int $size
     * @return float - bytes
     */
    public function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }

        return round($size);
    }

    /**
     * @param \Botble\Media\Models\MediaFile|\Illuminate\Database\Eloquent\Model $file
     * @param string|null $folderPath
     * @return bool
     */
    public function generateThumbnails(MediaFile $file): bool
    {
        if (!$file->canGenerateThumbnails()) {
            return false;
        }

        foreach ($this->getSizes() as $size) {
            $readableSize = explode('x', $size);
            $this->thumbnailService
                ->setImage(Storage::path($file->url))
                ->setSize($readableSize[0], $readableSize[1])
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . '-' . $size . '.' . File::extension($file->url))
                ->save();
        }

        if (config('core.media.media.watermark.source')) {
            $image = Image::make(Storage::path($file->url));
            $image->insert(
                config('core.media.media.watermark.source'),
                config('core.media.media.watermark.position', 'bottom-right'),
                config('core.media.media.watermark.x', 10),
                config('core.media.media.watermark.y', 10)
            );
            $image->save(Storage::path($file->url));
        }

        return true;
    }

    /**
     * @param \Botble\Media\Models\MediaFile|\Illuminate\Database\Eloquent\Model $file
     * @return bool
     */
    public function deleteThumbnails(MediaFile $file): bool
    {
        if (!$file->canGenerateThumbnails()) {
            return false;
        }

        $filename = pathinfo($file->url, PATHINFO_FILENAME);
        $files = [];
        foreach ($this->getSizes() as $size) {
            $files[] = str_replace($filename, $filename . '-' . $size, $file->url);
        }

        return Storage::delete($files);
    }

    /**
     * @param \Botble\Media\Models\MediaFile|\Illuminate\Database\Eloquent\Model $file
     * @return bool
     */
    public function deleteFile(MediaFile $file): bool
    {
        $this->deleteThumbnails($file);

        return Storage::delete($file->url);
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     */
    public function setPermissions(array $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @param string $permission
     */
    public function removePermission($permission)
    {
        Arr::forget($this->permissions, $permission);
    }

    /**
     * @param string $permission
     */
    public function addPermission($permission)
    {
        $this->permissions[] = $permission;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        return in_array($permission, $this->permissions);
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        $hasPermission = false;
        foreach ($permissions as $permission) {
            if (in_array($permission, $this->permissions)) {
                $hasPermission = true;
                break;
            }
        }

        return $hasPermission;
    }

    /**
     * @param string $path
     * @return string
     */
    public function url($path): string
    {
        if (Str::contains($path, 'https://')) {
            return $path;
        }

        return Storage::url($path);
    }

    /**
     * @return string
     */
    public function getSize(string $name): ?string
    {
        return config('core.media.media.sizes.' . $name);
    }

    /**
     * @param string $name
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function addSize(string $name, int $width, int $height): self
    {
        config(['core.media.media.sizes.' . $name => $width . 'x' . $height]);

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeSize(string $name): self
    {
        $sizes = $this->getSizes();
        Arr::forget($sizes, $name);

        config(['core.media.media.sizes' => $sizes]);

        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|JsonResponse|\Illuminate\Http\Response
     */
    public function uploadFromEditor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response('<script>alert("' . trans('core/media::media.can_not_detect_file_type') . '")</script>')
                ->header('Content-Type', 'text/html');
        }

        $result = $this->handleUpload($request->file('upload'), 0, $request->input('upload_type'));

        if ($result['error'] == false) {
            $file = $result['data'];
            if ($request->input('upload_type') == 'tinymce') {
                return response('<script>parent.setImageValue("' . $this->url($file->url) . '"); </script>')->header('Content-Type',
                    'text/html');
            }

            if (!$request->input('CKEditorFuncNum')) {
                return response()->json([
                    'fileName' => File::name($this->url($file->url)),
                    'uploaded' => 1,
                    'url'      => $this->url($file->url),
                ]);
            }

            return response('<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("' . $request->input('CKEditorFuncNum') . '", "' . $this->url($file->url) . '", "");</script>')
                ->header('Content-Type', 'text/html');
        }

        return response('<script>alert("' . Arr::get($result, 'message') . '")</script>')
            ->header('Content-Type', 'text/html');
    }

    /**
     * @param bool $relative
     * @return string
     */
    public function getDefaultImage(bool $relative = true): string
    {
        $default = config('core.media.media.default_image');

        if ($default && !$relative) {
            return url($default);
        }

        return $default;
    }

    /**
     * @param string|null $url
     * @param null $size
     * @param bool $relativePath
     * @param null $default
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string|string[]|null
     */
    public function getImageUrl($url, $size = null, $relativePath = false, $default = null)
    {
        if (empty($url)) {
            return $default;
        }

        if ($url == $this->getDefaultImage()) {
            return url($url);
        }

        if ($size && array_key_exists($size, $this->getSizes())) {
            $url = str_replace(
                File::name($url) . '.' . File::extension($url),
                File::name($url) . '-' . $this->getSize($size) . '.' . File::extension($url),
                $url
            );
        }

        if ($relativePath) {
            return $url;
        }

        if ($url == '__image__') {
            return $this->url($default);
        }

        return $this->url($url);
    }

    /**
     * @param string $image
     * @param null $size
     * @param bool $relativePath
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getObjectImage($image, $size = null, $relativePath = false)
    {
        if (!empty($image)) {
            if (empty($size) || $image == '__value__') {
                if ($relativePath) {
                    return $image;
                }

                return $this->url($image);
            }
            return $this->getImageUrl($image, $size, $relativePath);
        }

        return $this->getImageUrl($this->getDefaultImage(), null, $relativePath);
    }

    /**
     * @param string $mimeType
     * @return bool
     */
    public function isImage($mimeType)
    {
        return Str::startsWith($mimeType, 'image/');
    }
}
