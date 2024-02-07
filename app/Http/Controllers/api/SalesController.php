<?php

namespace App\Http\Controllers\api;

use Exception;
use Carbon\Carbon;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use App\Services\DatatableService;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\CustomExceptionHandler;
use App\Repositories\TransactionRepository;

class SalesController extends Controller
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
                new Sales(),
                $perPage,
                $search,
                $sortField,
                $sortDirection,
                'sales.id',
                ['product', 'transaction']
            );
            return response()->json($data);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Gagal memuat data!', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $filePath = storage_path('app/public/data-sales.xlsx');
            (new FastExcel($this->getSource($request->all())))->export($filePath, function ($att) {
                return [
                    'No transaksi'  => $att->transaction_number,
                    'Tanggal'       => $att->tanggal,
                    'Nama obat'     => $att->name,
                    'Jumlah'        => $att->quantity,
                    'Satuan'        => $att->type,
                    'Harga satuan'  => $att->price,
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
        $query = Sales::query();
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
