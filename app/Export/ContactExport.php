<?php

namespace App\Export;

use App\Models\Contacts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactExport implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'mobile_no',

        ];
    }
    public function collection()
    {
        return Contacts::select('id', 'name', 'email', 'mobile_no')->get();
    }


}
