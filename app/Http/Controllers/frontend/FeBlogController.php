<?php

namespace App\Http\Controllers\frontend;

use App\Enums\BlogTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\BlogRepository;

class FeBlogController extends Controller
{
    protected $blogRepo;

    function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepo = $blogRepository;
    }

    private function getBlogs($type, $isRecomended = false)
    {
        try {
            $data = $this->blogRepo->getBlogsByTypeKey($type, $isRecomended);
            return HttpHelper::successResponse('Blog data.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Failed to load data blog!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDestinations(Request $request)
    {
        $isRecomended = $request->input('is_recomended', false);
        return $this->getBlogs(BlogTypeEnum::DESTINATION, $isRecomended);
    }

    public function getFestivals(Request $request)
    {
        $isRecomended = $request->input('is_recomended', false);
        return $this->getBlogs(BlogTypeEnum::FESTIVAL, $isRecomended);
    }

    public function getInspiration(Request $request)
    {
        $isRecomended = $request->input('is_recomended', false);
        return $this->getBlogs(BlogTypeEnum::INSPIRATION, $isRecomended);
    }

    public function getTrips(Request $request)
    {
        $isRecomended = $request->input('is_recomended', false);
        return $this->getBlogs(BlogTypeEnum::TRIP, $isRecomended);
    }

    public function getCulinaries(Request $request)
    {
        $isRecomended = $request->input('is_recomended', false);
        return $this->getBlogs(BlogTypeEnum::CULINARY, $isRecomended);
    }
}
