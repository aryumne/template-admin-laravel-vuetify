<?php

namespace App\Http\Controllers\api;

use Exception;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use App\Services\DatatableService;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Validator;
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
            $filePath = storage_path('app/public/data-obat.xlsx');
            (new FastExcel($this->attendanceGenerator($request->all())))->export($filePath, function ($att) {
                return [
                    'Nama obat'     => $att->name,
                    'Barcode'       => $att->barcode,
                    'Nomor batch'   => isset($att->batch_number) ? $att->batch_number : " ",
                    'Jenis'         => $att->type,
                    'Tanggal kedaluarsa' => $att->expired_date,
                    'Stok box'      => $att->pack_stok,
                    'Harga per box' => $att->pack_price,
                    'Pcs per box'   => $att->items_per_pack,
                    'Total pcs'     => $att->total_item,
                    'Harga pe pcs'  => $att->item_price,
                ];
            });
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (CustomExceptionHandler $e) {
            return HttpHelper::errorResponse($e->getMessage(), [], $e->getCodeStatus());
        } catch (Exception $e) {
            return HttpHelper::errorResponse('Proses download gagal!', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function attendanceGenerator($data)
    {
        $query = Product::query();
        if ($data['is_by_selected'] == 1) {
            $query->whereIn('id', $data['selected_ids'])->orderBy('created_at', 'asc');
        } else {
            $startDate = Carbon::parse($data['start_date'])->startOfDay();
            $endDate = Carbon::parse($data['end_date'])->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'asc');
        }
        foreach ($query->cursor() as $att) {
            $att->type = $att->productType->name;
            yield $att;
        }
    }
}
