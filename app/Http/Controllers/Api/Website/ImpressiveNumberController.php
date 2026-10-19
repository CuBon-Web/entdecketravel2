<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\models\website\ImpressiveNumber;
use Illuminate\Http\Request;

class ImpressiveNumberController extends Controller
{
    public function createOrUpdate(Request $request)
    {
        if ($request->data) {
            ImpressiveNumber::truncate();
            foreach ($request->data as $index => $value) {
                ImpressiveNumber::create([
                    'name' => $value['name'] ?? '',
                    'value' => $value['value'] ?? '',
                    'suffix' => $value['suffix'] ?? '',
                    'image' => $value['image'] ?? '',
                    'status' => $value['status'] ?? 1,
                    'sort_order' => $value['sort_order'] ?? ($index + 1),
                ]);
            }
        }

        return response()->json([
            'messenge' => 'success',
        ], 200);
    }

    public function list()
    {
        $data = ImpressiveNumber::orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->get();

        return response()->json([
            'messenge' => 'success',
            'data' => $data,
        ], 200);
    }
}
