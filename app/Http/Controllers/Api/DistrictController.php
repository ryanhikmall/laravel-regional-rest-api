<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DistrictModel; // ASUMSI
use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    // Mengambil semua data kecamatan
    public function index(Request $request)
    {
        $district = DistrictModel::orderBy('district_id', 'ASC')->get();
        
        $response = ApiFormatter::createJson(200, 'Get All District Success', $district);
        return response()->json($response);
    }

    // Membuat data kecamatan baru
    public function create(Request $request)
    {
        try {
            $params = $request->all();

            $validator = Validator::make($params, [
                'code' => 'required|max:10',
                'name' => 'required',
                'city_id' => 'required|numeric' // Asumsi FK ke Kota
            ], [
                'code.required' => 'District Code is required',
                'code.max' => 'District Code must not exceed 10 characters',
                'name.required' => 'District Name is required',
                'city_id.required' => 'City ID is required',
            ]);

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response);
            }

            $district = [
                'district_code' => $params['code'],
                'district_name' => $params['name'],
                'city_id' => $params['city_id'],
            ];

            $data = DistrictModel::create($district);
            $createdDistrict = DistrictModel::find($data->district_id);

            $response = ApiFormatter::createJson(200, 'Create District Success', $createdDistrict);
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }

    // Mengambil detail kecamatan
    public function detail($id)
    {
        try {
            $district = DistrictModel::find($id);

            if (is_null($district)) {
                $response = ApiFormatter::createJson(404, 'District Not Found');
                return response()->json($response, 404);
            }

            $response = ApiFormatter::createJson(200, 'Get Detail District Success', $district);
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(400, $e->getMessage());
            return response()->json($response);
        }
    }
    
    // Memperbarui data kecamatan (UPDATE - PUT)
    public function update(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preDistrict = DistrictModel::find($id);

            if (is_null($preDistrict)) {
                $response = ApiFormatter::createJson(404, 'Data Not Found');
                return response()->json($response, 404);
            }

            $validator = Validator::make($params, [
                'code' => 'required|max:10',
                'name' => 'required',
                'city_id' => 'required|numeric'
            ], [
                'code.required' => 'District Code is required',
                'name.required' => 'District Name is required',
                'city_id.required' => 'City ID is required',
            ]);

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response);
            }

            $district = [
                'district_code' => $params['code'],
                'district_name' => $params['name'],
                'city_id' => $params['city_id'],
            ];

            $preDistrict->update($district);
            $updatedDistrict = $preDistrict->fresh();

            $response = ApiFormatter::createJson(200, 'Update District Success', $updatedDistrict);
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }
    
    // Memperbarui data kecamatan sebagian (PATCH)
    public function patch(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preDistrict = DistrictModel::find($id);

            if (is_null($preDistrict)) {
                $response = ApiFormatter::createJson(404, 'Data Not Found');
                return response()->json($response, 404);
            }

            $district = [];

            if (isset($params['code'])) {
                $validator = Validator::make($params, ['code' => 'required|max:10'], ['code.required' => 'District Code is required',]);
                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                    return response()->json($response);
                }
                $district['district_code'] = $params['code'];
            }

            if (isset($params['name'])) {
                $validator = Validator::make($params, ['name' => 'required'], ['name.required' => 'District Name is required',]);
                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                    return response()->json($response);
                }
                $district['district_name'] = $params['name'];
            }
            
            if (isset($params['city_id'])) {
                $validator = Validator::make($params, ['city_id' => 'required|numeric'], ['city_id.required' => 'City ID is required',]);
                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                    return response()->json($response);
                }
                $district['city_id'] = $params['city_id'];
            }
            
            if (empty($district)) {
                 $response = ApiFormatter::createJson(400, 'No data provided for update');
                 return response()->json($response, 400);
            }

            $preDistrict->update($district);
            $updatedDistrict = $preDistrict->fresh();

            $response = ApiFormatter::createJson(200, 'Update District Success', $updatedDistrict);
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }
    
    // Menghapus data kecamatan
    public function delete($id)
    {
        try {
            $district = DistrictModel::find($id);

            if (is_null($district)) {
                $response = ApiFormatter::createJson(404, 'Data Not Found');
                return response()->json($response, 404);
            }

            $district->delete();

            $response = ApiFormatter::createJson(200, 'Delete District Success');
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }
    // ... fungsi delete dan lainnya di atas ...

    // TAMBAHKAN FUNGSI INI
    public function getByCity($city_id)
    {
        try {
            // Cari data kecamatan berdasarkan city_id
            $data = DistrictModel::where('city_id', $city_id)->get();

            if ($data->count() > 0) {
                $response = ApiFormatter::createJson(200, 'Get District by City Success', $data);
                return response()->json($response);
            } else {
                $response = ApiFormatter::createJson(404, 'Data Not Found for this City ID');
                return response()->json($response, 404);
            }
        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }
}