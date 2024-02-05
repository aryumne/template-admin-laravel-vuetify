<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use App\Services\DatatableService;
use App\Http\Controllers\Controller;
use App\Repositories\PurchaseRepository;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    protected $purchaseRepo, $paginateValue;

    function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepo    = $purchaseRepository;
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
            $sortField      = trim($request->input('sort.field', 'created_at'));
            $sortDirection  = trim($request->input('sort.direction')) == 'asc' ? 'asc' : 'desc';
            $data           = DatatableService::getDataForTable(
                new Purchase(),
                $perPage,
                $search,
                $sortField,
                $sortDirection,
                'purchases.id',
                ['product']
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
    public function store(PurchaseRequest $request)
    {
        try {
            $result = $this->purchaseRepo->store($request->only(['stocks']));
            return HttpHelper::successResponse('Stock Obat berhasil disimpan.', $result, Response::HTTP_CREATED);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Gagal menyimpan data!', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
