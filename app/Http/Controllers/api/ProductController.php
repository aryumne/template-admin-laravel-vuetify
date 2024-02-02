<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use App\Services\DatatableService;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Exceptions\CustomExceptionHandler;

class ProductController extends Controller
{
    protected $productRepo, $paginateValue;

    function __construct(ProductRepository $productRepository)
    {
        $this->productRepo    = $productRepository;
        $this->paginateValue  = env('PAGINATE_VALUE');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage        = $request->input('entries', $this->paginateValue);
            $search         = $request->input('search', '');
            $sortField      = $request->input('sort.field', 'name');
            $sortDirection  = $request->input('sort.direction') == 'asc' ? 'asc' : 'desc';
            $data           = DatatableService::getDataForTable(
                new Product(),
                $perPage,
                $search,
                $sortField,
                $sortDirection,
                'products.id'
            );
            return response()->json($data);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Gagal memuat data!', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
