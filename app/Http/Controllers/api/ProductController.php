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
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;

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
            $perPage        = trim($request->input('entries', $this->paginateValue));
            $search         = trim($request->input('search', ''));
            $sortField      = trim($request->input('sort.field', 'name'));
            $sortDirection  = trim($request->input('sort.direction')) == 'asc' ? 'asc' : 'desc';
            $data           = DatatableService::getDataForTable(
                new Product(),
                $perPage,
                $search,
                $sortField,
                $sortDirection,
                'products.id',
                ['productType']
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
    public function store(ProductRequest $request)
    {
        try {
            $result = $this->productRepo->store($request->only(['name', 'batch_number', 'pack_stok', 'items_per_pack', 'total_item', 'pack_price', 'item_price', 'expired_date', 'product_type_id']));
            return HttpHelper::successResponse('Data obat baru berhasil disimpan.', $result, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Gagal menyimpan data!', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        try {
            $data = $this->productRepo->getById($uuid);
            return HttpHelper::successResponse('Data obat.', new ProductResource($data), Response::HTTP_OK);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Gagal memuat data obat!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        try {
            $data = $this->productRepo->update($request->all(), $uuid);
            return HttpHelper::successResponse('Perubahan data berhasil disimpan.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return HttpHelper::errorResponse('Gagal menyimpan perubahan!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        try {
            $this->productRepo->delete($uuid);
            return HttpHelper::successResponse('Data berhasil dihapus.', [], Response::HTTP_OK);
        } catch (\Exception $e) {
            return HttpHelper::errorResponse('Gagal menghapus data!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function search(Request $request)
    {

        try {
            $search = trim($request->input('search', ''));
            $data   = $this->productRepo->search($search, $this->paginateValue);
            if ($data) $data = ProductResource::collection($data);
            return HttpHelper::successResponse('Hasil pencarian.', $data, Response::HTTP_OK);
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Gagal memuat data!', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchByBarcode(string $barcode)
    {
        try {
            $data = $this->productRepo->getOneByCondition([
                "key" => "barcode",
                "value" => $barcode
            ]);
            return HttpHelper::successResponse('Data obat.', new ProductResource($data), Response::HTTP_OK);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Gagal memuat data obat!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
