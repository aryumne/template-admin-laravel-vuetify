<?php

namespace App\Http\Controllers\api;

use Exception;
use Carbon\Carbon;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use App\Services\DatatableService;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Requests\PurchaseRequest;
use App\Repositories\PurchaseRepository;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\CustomExceptionHandler;

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

    public function download(Request $request)
    {
        if (!$request->has('is_by_selected')) return HttpHelper::errorValidation('Silahkan pilih jenis download berdasarkan tanggal atau per data!', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        $rules = [];
        if ($request->is_by_selected == 1) {
            $rules['selected_ids'] = ['required', 'array', 'min:1'];
        } else {
            $rules['start_date'] = ['required', 'date'];
            $rules['end_date'] = ['required', 'date'];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())  return HttpHelper::errorValidation($validator->errors()->first(), $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        try {
            $filePath = storage_path('app/public/data-purchases.xlsx');
            (new FastExcel($this->getSource($request->all())))->export($filePath, function ($att) {
                return [
                    'Tanggal'       => $att->tanggal,
                    'Nama obat'     => $att->name,
                    'Jumlah (box)'  => $att->quantity,
                    'Harga satuan (box)' => $att->price,
                    'Total'         => $att->total_price,
                ];
            });
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Proses download gagal!', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getSource($data)
    {
        $query = Purchase::query();
        if ($data['is_by_selected'] == 1) {
            $query->whereIn('id', $data['selected_ids'])->orderBy('created_at', 'asc');
        } else {
            $startDate = Carbon::parse($data['start_date'])->startOfDay();
            $endDate = Carbon::parse($data['end_date'])->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'asc');
        }
        foreach ($query->cursor() as $att) {
            $att->tanggal = Carbon::parse($att->created_at)->format('Y-m-d');
            yield $att;
        }
    }
}
