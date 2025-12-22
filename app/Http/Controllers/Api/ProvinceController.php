<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProvinceModel; // Pastikan huruf besar/kecil sesuai file model
use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $province = ProvinceModel::orderBy('province_id', 'ASC')->get();
        // Langsung return Helper
        return ApiFormatter::createJson(200, 'Get Data Success', $province);
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
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $province = [
                'province_code' => $params['code'],
                'province_name' => $params['name'],
            ];

            $data = ProvinceModel::create($province);
            // $createdProvince = ProvinceModel::find($data->province_id); // Tidak perlu query ulang
            
            return ApiFormatter::createJson(200, 'Create Province Success', $data);

        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            $province = ProvinceModel::find($id);

            if (is_null($province)) {
                return ApiFormatter::createJson(404, 'Province Not Found');
            }

            return ApiFormatter::createJson(200, 'Get Detail Province Success', $province);

        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preProvince = ProvinceModel::find($id);

            if (is_null($preProvince)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

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
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $province = [
                'province_code' => $params['code'],
                'province_name' => $params['name'],
            ];

            $preProvince->update($province);
            $updatedProvince = $preProvince->fresh();

            return ApiFormatter::createJson(200, 'Update Province Success', $updatedProvince);

        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $province = ProvinceModel::find($id);

            if (is_null($province)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            $province->delete();

            return ApiFormatter::createJson(200, 'Delete Province Success');

        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }
}