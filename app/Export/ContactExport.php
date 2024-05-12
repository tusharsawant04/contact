<?php

namespace App\Export;

use App\Models\Contacts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactExport implements FromCollection
{
    public $contacts;

    public function __construct($contacts) {
        $this->contact = $contacts;
    }
    public function headings(): array
    {
        return [
            'name',
            'email',
            'mobile_no',
        ];
    }
    public function collection()
    {
        return contacts::all();
    }


}
