<?php

namespace Botble\Media\Http\Controllers;

use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Botble\Media\Http\Requests\MediaFileRequest;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use RvMedia;
use Storage;
use Validator;

/**
 * @since 19/08/2015 07:50 AM
 */
class MediaFileController extends Controller
{
    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;

    /**
     * @var MediaFolderInterface
     */
    protected $folderRepository;

    /**
     * @param MediaFileInterface $fileRepository
     * @param MediaFolderInterface $folderRepository
     */
    public function __construct(MediaFileInterface $fileRepository, MediaFolderInterface $folderRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->folderRepository = $folderRepository;
    }

    /**
     * @param MediaFileRequest $request
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function postUpload(MediaFileRequest $request)
    {
        $result = RvMedia::handleUpload(Arr::first($request->file('file')), $request->input('folder_id', 0));

        if ($result['error'] == false) {
            return RvMedia::responseSuccess([
                'id'  => $result['data']->id,
                'src' => Storage::url($result['data']->url),
            ]);
        }

        return RvMedia::responseError($result['message']);
    }

    /**
     * @param Request $request
     * @return ResponseFactory|JsonResponse|Response
     * @throws FileNotFoundException
     */
    public function postUploadFromEditor(Request $request)
    {
        return RvMedia::uploadFromEditor($request);
    }
}
