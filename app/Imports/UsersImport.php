<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'product_name'     => $row[0],
            'product_code'    => $row[1], 
            'product_price' => $row[2],
            'product_detail' => $row[3],
            'varriant_array' => $row[4],
            'product_cost' => $row[5],
            'created_at' => date_default_timezone_get(),
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
