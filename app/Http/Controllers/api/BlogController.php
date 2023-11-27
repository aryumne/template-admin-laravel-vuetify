<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\BlogRepository;

class BlogController extends Controller
{
    protected $blogRepo;

    function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepo = $blogRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->blogRepo->getAll();
            return HttpHelper::successResponse('Blog data.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Failed to load data blog!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function groupByTypeKey()
    {
        try {
            $data = $this->blogRepo->getAllWithGrouping();
            return HttpHelper::successResponse('Blog data.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Failed to load data blog!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        try {
            $data = $this->blogRepo->store($request->all());
            Log::info("Creating Blog", ['data' => $data->id]);
            return HttpHelper::successResponse('a new blog has been successfully created.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error("Creating Blog", ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Failed to store data blog!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        try {
            $data = $this->blogRepo->getOneByCondition(['key' => 'id', 'value' => $uuid]);
            return HttpHelper::successResponse('Blog data.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error("Getting blog", ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Failed to load data blog!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        try {
            $data = $this->blogRepo->update($request->all(), $uuid);
            Log::info("Updating Blog", ['data' => $data->id]);
            return HttpHelper::successResponse('The blog has been successfully updated.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error("Updating Blog", ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Failed to update data blog!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        try {
            $this->blogRepo->delete($uuid);
            Log::info("Deleting Blog", ['data' => $uuid]);
            return HttpHelper::successResponse('The blog is successfully deleted.', [], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error("Deleting Blog", ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Failed to delete the blog!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
