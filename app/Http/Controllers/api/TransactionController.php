<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Helpers\HttpHelper;
use App\Services\DatatableService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Resources\TransactionResource;
use App\Repositories\TransactionRepository;

class TransactionController extends Controller
{
    protected $trxRepo, $paginateValue;

    function __construct(TransactionRepository $trxRepository)
    {
        $this->trxRepo    = $trxRepository;
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
                new Transaction(),
                $perPage,
                $search,
                $sortField,
                $sortDirection,
                'transactions.id',
                ['sales']
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
    public function store(TransactionRequest $request)
    {
        try {
            $result = $this->trxRepo->store($request->only(['amount', 'cash_amount', 'return_amount', 'prescription_number', 'orders']));
            return HttpHelper::successResponse('Transaksi berhasil disimpan.', $result, Response::HTTP_CREATED);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
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
            $data = $this->trxRepo->getById($uuid, ['sales']);
            return HttpHelper::successResponse('Data obat.', new TransactionResource($data), Response::HTTP_OK);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Gagal memuat data obat!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function downloadInvoice(string $uuid)
    {
        try {
            $data = $this->trxRepo->getById($uuid, ['sales']);
            $pdf = Pdf::loadView('invoice', ['data' => $data]);
            return $pdf->download('invoice.pdf');
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
            $data = $this->trxRepo->update($request->all(), $uuid);
            return HttpHelper::successResponse('Perubahan data berhasil disimpan.', $data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return HttpHelper::errorResponse('Gagal menyimpan perubahan!', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
