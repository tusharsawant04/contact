<?php

namespace App\Import;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use  App\Models\Contacts;
class ContactImport implements ToCollection
{
    /**
     * Import data from the uploaded file.
     *
     * @param  Collection  $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

         Contacts::create([
                'name' => $row[1],
                'email' => $row[2],
                'mobile_no' => $row[3],
            ]);
        }
    }
}
