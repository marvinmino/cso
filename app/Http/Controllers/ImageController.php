<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ImageStoreRequest;
use App\Repositories\ImageRepository;
use App\Models\User;
use App\Models\Agency;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Trip;
use Illuminate\Support\Facades\Gate;
use \Auth;
use App\Http\Resources\ImageResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    /**
     *@var imageRepository
     */

    private ImageRepository $imageRepository;


    /**
     *
     * @param ImageRepository $imageRepository
     */
    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }


    /**
     *
     * @param Request $request
     * @return void
     */
    public function store($trip, ImageStoreRequest $request)
    {
        if (!Gate::allows('owns-trip', $trip)) {
            return $this->errorResponse('Bad Request', Response::HTTP_BAD_REQUEST);
        }

        try {
            return new ImageResource($this->imageRepository->save($trip, $request));
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     *
     * @param  $image
     * @param Request $request
     * @return void
     */
    public function show($image, Request $request)
    {
        try {
            $image = Image::findOrFail($image);
            return new ImageResource($image);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('Image not found', Response::HTTP_NOT_FOUND);
        }
    }



    /**
     *
     * @param  $Image
     * @param Request $request
     * @return void
     */
    public function destroy($image, Request $request)
    {
        if (Gate::allows('owns-Image', $request->image_id)) {
            return $this->errorResponse('Image not found', Response::HTTP_NOT_FOUND);
        }
        try {
            $this->imageRepository->delete($request->image_id);
            return $this->successResponse('Image Deleted', Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * @return void
     */
    public function index()
    {
        $images = Image::paginate(10);
        return ImageResource::collection($images);
    }
}
