<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\NewsStoreRequest;
use App\Services\NewsService;
use App\Models\User;
use App\Models\News;
use \Auth;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    /**
     *@var newsService
     */

    private NewsService $newsService;


    /**
     *
     * @param newsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }


    /**
     *
     * @param Request $request
     * @return void
     */
    public function store(NewsStoreRequest $request)
    {
        try {
            $data = $this->newsService->save($request->all());
            return new NewsResource($data);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     *
     * @param  $news
     * @param Request $request
     * @return void
     */
    public function show($news, Request $request)
    {
        try {
            $news = news::findOrFail($news);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('News not found', Response::HTTP_NOT_FOUND);
        }
        return new NewsResource($news);
    }



    /**
     *
     * @param  $news
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        try {
            $this->newsService->delete($request->news_id);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('Not found', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('News Deleted', Response::HTTP_NO_CONTENT);
    }



    /**
     *
     * @param  $news
     * @param Request $request
     * @return void
     */
    public function update(NewsStoreRequest $request)
    {
        try {
            $this->newsService->update($request->all(), $request->input('news_id'));
        } catch (\Exception $e) {
            \Log::error($e);
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return $this->errorResponse('Not found', Response::HTTP_NOT_FOUND);
            }
            return $this->errorResponse('System Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse('News updated', Response::HTTP_ACCEPTED);
    }

    /**
     *
     * @param NewsFilterRequest $request
     * @return void
     */
    public function published(Request $request)
    {
        try {
            $data = $this->newsService->published($request);
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * @param NewsFilterRequest $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $data = $this->newsService->index($request->all());
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * @return void
     */
    public function publish(Request $request)
    {
        try {
            $this->newsService->publish($request->news_id);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse('News published', Response::HTTP_OK);
    }

    /**
     *
     * @return void
     */
    public function unpublish(Request $request)
    {
        try {
            $this->newsService->unpublish($request->news_id);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse('News unpublished', Response::HTTP_OK);
    }
}
