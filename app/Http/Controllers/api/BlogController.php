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
            Log::error($e->getMessage(), ['error' => $e]);
            HttpHelper::errorResponse('Failed to load data blog!', $e->getMessage(), Response::HTTP_NO_CONTENT);
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
            return HttpHelper::successResponse('New blog is successfully created.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['error' => $e]);
            HttpHelper::errorResponse('Failed to load data blog!', $e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        try {
            $data = $this->blogRepo->getById($uuid);
            return HttpHelper::successResponse('Blog data.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['error' => $e]);
            HttpHelper::errorResponse('Failed to load data blog!', $e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        //
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
            Log::error($e->getMessage(), ['error' => $e]);
            HttpHelper::errorResponse('Failed to delete the blog!', $e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }
}
