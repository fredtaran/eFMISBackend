<?php

namespace App\Imports;

use App\Models\Uacs;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class UacsImport implements ToCollection, ToModel
{
    private $rowCount = 0;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        
    }

    /**
     * @param array $row
     */
    public function model(array $row)
    {
        $this->rowCount++;

        if($this->rowCount > 1) {
            $count = Uacs::where('code', '=', $row[2])->count();
            if (empty($count)) {
                $uacs = new Uacs;
                $uacs->id      = $row[0];
                $uacs->title   = $row[1];
                $uacs->code    = $row[2];
                $uacs->save();
            }
        }
    }


    /**
     * Get the row count
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
