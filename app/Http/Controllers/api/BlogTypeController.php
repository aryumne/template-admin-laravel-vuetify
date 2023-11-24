<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\BlogTypeRepository;

class BlogTypeController extends Controller
{
    protected $btRepo;

    function __construct(BlogTypeRepository $btRepository)
    {
        $this->btRepo = $btRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->btRepo->getAll();
            return HttpHelper::successResponse('Blog type data.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error("Getting blog types", ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Failed to load data blog type!', [], Response::HTTP_NO_CONTENT);
        }
    }
}
