<?php
namespace App\Exports;

use App\Models\Pengukuran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengukuranExport implements FromCollection, WithHeadings
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return Pengukuran::whereBetween('tanggal_pengukuran', [$this->start_date, $this->end_date])
            ->with('remaja.user')
            ->get()
            ->map(function ($pengukuran) {
                return [
                    'ID' => $pengukuran->id,
                    'Username' => $pengukuran->remaja->user->username,
                    'Nama' => $pengukuran->remaja->user->nama,
                    'Jenis Kelamin' => $pengukuran->remaja->jenis_kelamin,
                    'Tanggal Lahir' => $pengukuran->remaja->tanggal_lahir,
                    'Tanggal Pengukuran' => $pengukuran->tanggal_pengukuran,
                    'Usia' => \Carbon\Carbon::parse($pengukuran->remaja->tanggal_lahir)->age,
                    'BB' => $pengukuran->bb,
                    'TB' => $pengukuran->tb,
                    'Status Gizi' => $pengukuran->status_gizi,
                    'LILA' => $pengukuran->lila,
                    'Tensi' => $pengukuran->tensi,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Username',
            'Nama',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Tanggal Pengukuran',
            'Usia',
            'BB',
            'TB',
            'Status Gizi',
            'LILA',
            'Tensi',
        ];
    }
}
