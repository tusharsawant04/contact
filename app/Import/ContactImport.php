<?php

namespace App\Import;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use  App\Models\Contacts;
use Illuminate\Support\Facades\Validator;
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
            try {
                Contacts::create([
                    'name' => $row[1],
                    'email' => $row[2],
                    'mobile_no' => $row[3],
                ]);
            } catch (Exception $e) {
                Log::error("Error importing contact: " . $e->getMessage());
            }
        }
    }
}
