<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\provinceModel;

use App\Helpers\ApiFormatter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    public function index (Request $request)
    {
        $province = provinceModel::orderBy('province_id', 'ASC') -> get();

        // status code harusnya 200 untuk success
        $response = ApiFormatter::createJson(200, 'Get Data Success', $province);

        return response()->json($response);
    }

    public function create(Request $request)
{
    try {
        $params = $request->all();

        $validator = Validator::make($params,
            [
                'code' => 'required|max:10',
                'name' => 'required',
            ],
            [
                'code.required' => 'Province Code is required',
                'code.max' => 'Province Code must not exceed 10 characters',
                'name.required' => 'Province Name is required',
            ]
        );

        if ($validator->fails()) {
            $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            return response()->json($response);
        }

        $province = [
            'province_code' => $params['code'],
            'province_name' => $params['name'],
        ];

        $data = ProvinceModel::create($province);
        $createdProvince = ProvinceModel::find($data->province_id);

        $response = ApiFormatter::createJson(200, 'Create Province Success', $createdProvince);
        return response()->json($response);

    } catch (\Exception $e) {
        $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        return response()->json($response);
    }
}

    public function detail($id)
{
    try {
        $province = ProvinceModel::find($id);

        if (is_null($province)) {
            // Mengembalikan respons 404 jika provinsi tidak ditemukan
            return ApiFormatter::createJson(404, 'Province Not Found');
        }

        // Mengembalikan respons 200 (Success) dengan data provinsi
        $response = ApiFormatter::createJson(200, 'Get Detail Province Success', $province);
        return response()->json($response);

    } catch (\Exception $e) {
        // Mengembalikan respons 400 (Bad Request/Client Error) jika terjadi exception
        $response = ApiFormatter::createJson(400, $e->getMessage());
        return response()->json($response);
    }
}

    public function update(Request $request, $id)
{
    try {
        $params = $request->all();

        // 1. Cari data provinsi berdasarkan ID
        $preProvince = ProvinceModel::find($id);

        // Jika data tidak ditemukan, kembalikan respons 404
        if (is_null($preProvince)) {
            return ApiFormatter::createJson(404, 'Data Not Found');
        }

        // 2. Lakukan validasi data input
        $validator = Validator::make($params,
            [
                'code' => 'required|max:10',
                'name' => 'required',
            ],
            [
                'code.required' => 'Province Code is required',
                'code.max' => 'Province Code must not exceed 10 characters',
                'name.required' => 'Province Name is required',
            ]
        );

        // Jika validasi gagal, kembalikan respons 400 (Bad Request)
        if ($validator->fails()) {
            $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            return response()->json($response);
        }

        // 3. Siapkan array data untuk update
        $province = [
            'province_code' => $params['code'],
            'province_name' => $params['name'],
        ];

        // 4. Lakukan update dan ambil data terbaru
        $preProvince->update($province);
        $updatedProvince = $preProvince->fresh(); // Ambil data setelah diupdate

        // 5. Kembalikan respons 200 (Success)
        $response = ApiFormatter::createJson(200, 'Update Province Success', $updatedProvince);
        return response()->json($response);

    } catch (\Exception $e) {
        // Tangani Internal Server Error
        $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        return response()->json($response);
    }
}

    public function patch(Request $request, $id)
{
    try {
        $params = $request->all();

        // 1. Cari data provinsi berdasarkan ID
        $preProvince = ProvinceModel::find($id);

        // Jika data tidak ditemukan, kembalikan respons 404
        if (is_null($preProvince)) {
            return ApiFormatter::createJson(404, 'Data Not Found');
        }

        // 2. Inisialisasi array untuk data yang akan di-update
        $province = [];

        // 3. Periksa dan validasi field 'code'
        if (isset($params['code'])) {
            $validator = Validator::make(
                $params,
                [
                    'code' => 'required|max:10',
                ],
                [
                    'code.required' => 'Province Code is required',
                    'code.max' => 'Province Code must not exceed 10 characters',
                ]
            );

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response);
            }

            $province['province_code'] = $params['code'];
        }

        // 4. Periksa dan validasi field 'name'
        if (isset($params['name'])) {
            $validator = Validator::make(
                $params,
                [
                    'name' => 'required',
                ],
                [
                    'name.required' => 'Province Name is required',
                ]
            );

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response);
            }

            $province['province_name'] = $params['name'];
        }

        // 5. Lakukan update hanya pada field yang ada dalam array $province
        $preProvince->update($province);
        $updatedProvince = $preProvince->fresh(); // Ambil data setelah diupdate

        // 6. Kembalikan respons 200 (Success)
        $response = ApiFormatter::createJson(200, 'Update Province Success', $updatedProvince);
        return response()->json($response);

    } catch (\Exception $e) {
        // Tangani Internal Server Error
        $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        return response()->json($response);
    }
}

    public function delete($id)
{
    try {
        // 1. Cari data provinsi berdasarkan ID
        $province = ProvinceModel::find($id);

        // 2. Periksa apakah data ditemukan (null)
        if (is_null($province)) {
            // Jika tidak ditemukan, kembalikan respons 404
            return ApiFormatter::createJson(404, 'Data Not Found');
        }

        // 3. Hapus data provinsi yang ditemukan
        $province->delete();

        // 4. Kembalikan respons 200 (Success)
        $response = ApiFormatter::createJson(200, 'Delete Province Success');
        return response()->json($response);

    } catch (\Exception $e) {
        // Tangani Internal Server Error
        $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        return response()->json($response);
    }
}

}


