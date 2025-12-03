<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityModel; // ASUMSI
use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    // Mengambil semua data kota
    public function index(Request $request)
    {
        $city = CityModel::orderBy('city_id', 'ASC')->get();
        
        $response = ApiFormatter::createJson(200, 'Get All City Success', $city);
        return response()->json($response);
    }

    // Membuat data kota baru
    public function create(Request $request)
    {
        try {
            $params = $request->all();

            $validator = Validator::make($params, [
                'code' => 'required|max:10',
                'name' => 'required',
                'province_id' => 'required|numeric' // Asumsi FK ke Provinsi
            ], [
                'code.required' => 'City Code is required',
                'code.max' => 'City Code must not exceed 10 characters',
                'name.required' => 'City Name is required',
                'province_id.required' => 'Province ID is required',
            ]);

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response);
            }

            $city = [
                'city_code' => $params['code'],
                'city_name' => $params['name'],
                'province_id' => $params['province_id'],
            ];

            $data = CityModel::create($city);
            $createdCity = CityModel::find($data->city_id);

            $response = ApiFormatter::createJson(200, 'Create City Success', $createdCity);
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }

    // Mengambil detail kota
    public function detail($id)
    {
        try {
            $city = CityModel::find($id);

            if (is_null($city)) {
                $response = ApiFormatter::createJson(404, 'City Not Found');
                return response()->json($response, 404);
            }

            $response = ApiFormatter::createJson(200, 'Get Detail City Success', $city);
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(400, $e->getMessage());
            return response()->json($response);
        }
    }
    
    // Memperbarui data kota (UPDATE - PUT)
    public function update(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preCity = CityModel::find($id);

            if (is_null($preCity)) {
                $response = ApiFormatter::createJson(404, 'Data Not Found');
                return response()->json($response, 404);
            }

            $validator = Validator::make($params, [
                'code' => 'required|max:10',
                'name' => 'required',
                'province_id' => 'required|numeric'
            ], [
                'code.required' => 'City Code is required',
                'name.required' => 'City Name is required',
                'province_id.required' => 'Province ID is required',
            ]);

            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response);
            }

            $city = [
                'city_code' => $params['code'],
                'city_name' => $params['name'],
                'province_id' => $params['province_id'],
            ];

            $preCity->update($city);
            $updatedCity = $preCity->fresh();

            $response = ApiFormatter::createJson(200, 'Update City Success', $updatedCity);
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }
    
    // Memperbarui data kota sebagian (PATCH)
    public function patch(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preCity = CityModel::find($id);

            if (is_null($preCity)) {
                $response = ApiFormatter::createJson(404, 'Data Not Found');
                return response()->json($response, 404);
            }

            $city = [];

            if (isset($params['code'])) {
                $validator = Validator::make($params, ['code' => 'required|max:10'], ['code.required' => 'City Code is required',]);
                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                    return response()->json($response);
                }
                $city['city_code'] = $params['code'];
            }

            if (isset($params['name'])) {
                $validator = Validator::make($params, ['name' => 'required'], ['name.required' => 'City Name is required',]);
                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                    return response()->json($response);
                }
                $city['city_name'] = $params['name'];
            }
            
            if (isset($params['province_id'])) {
                $validator = Validator::make($params, ['province_id' => 'required|numeric'], ['province_id.required' => 'Province ID is required',]);
                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                    return response()->json($response);
                }
                $city['province_id'] = $params['province_id'];
            }
            
            if (empty($city)) {
                 $response = ApiFormatter::createJson(400, 'No data provided for update');
                 return response()->json($response, 400);
            }

            $preCity->update($city);
            $updatedCity = $preCity->fresh();

            $response = ApiFormatter::createJson(200, 'Update City Success', $updatedCity);
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }
    
    // Menghapus data kota
    public function delete($id)
    {
        try {
            $city = CityModel::find($id);

            if (is_null($city)) {
                $response = ApiFormatter::createJson(404, 'Data Not Found');
                return response()->json($response, 404);
            }

            $city->delete();

            $response = ApiFormatter::createJson(200, 'Delete City Success');
            return response()->json($response);

        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }

    public function getByProvince($province_id)
    {
        try {
            // Cari data kota berdasarkan province_id
            $data = CityModel::where('province_id', $province_id)->get();

            if ($data->count() > 0) {
                $response = ApiFormatter::createJson(200, 'Get City by Province Success', $data);
                return response()->json($response);
            } else {
                $response = ApiFormatter::createJson(404, 'Data Not Found for this Province ID');
                return response()->json($response, 404);
            }
        } catch (\Exception $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response);
        }
    }
}