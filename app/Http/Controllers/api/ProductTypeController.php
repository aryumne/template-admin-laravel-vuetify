<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use App\Http\Controllers\Controller;
use App\Repositories\ProductTypeRepository;

class ProductTypeController extends Controller
{
    protected $ptRepo;

    function __construct(ProductTypeRepository $ptRepository)
    {
        $this->ptRepo = $ptRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->ptRepo->getAll();
            return HttpHelper::successResponse('Product type data.', $data, Response::HTTP_OK);
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Failed to load data blog type!', [], Response::HTTP_NO_CONTENT);
        }
    }
}
