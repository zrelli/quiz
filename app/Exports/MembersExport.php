<?php
namespace App\Exports;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
class MembersExport implements FromQuery
{
    use Exportable;
    public function query()
    {
        return Member::query();
    }
}
