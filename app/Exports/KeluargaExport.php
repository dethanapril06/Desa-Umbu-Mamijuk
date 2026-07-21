<?php

namespace App\Exports;

use App\Models\Keluarga;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KeluargaExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithCustomValueBinder, ShouldAutoSize
{
    public function __construct(private readonly array $filters = [])
    {
    }

    public function collection(): Collection
    {
        return Keluarga::with(['rtRw.dusun', 'kepalaKeluarga', 'penduduk'])
            ->when($this->filters['rt_rw_id'] ?? null, fn (Builder $query, string $value) => $query->where('rt_rw_id', $value))
            ->when($this->filters['dusun_id'] ?? null, function (Builder $query, string $value) {
                $query->whereHas('rtRw', fn (Builder $rtRw) => $rtRw->where('dusun_id', $value));
            })
            ->when($this->filters['tanggal_terdaftar_mulai'] ?? null, fn (Builder $query, string $value) => $query->whereDate('tanggal_terdaftar', '>=', $value))
            ->when($this->filters['tanggal_terdaftar_selesai'] ?? null, fn (Builder $query, string $value) => $query->whereDate('tanggal_terdaftar', '<=', $value))
            ->when(($this->filters['status_kepala_keluarga'] ?? null) === 'ada', fn (Builder $query) => $query->whereHas('kepalaKeluarga'))
            ->when(($this->filters['status_kepala_keluarga'] ?? null) === 'belum_ada', fn (Builder $query) => $query->whereDoesntHave('kepalaKeluarga'))
            ->orderBy('no_kk')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. KK',
            'Kepala Keluarga',
            'Alamat',
            'RT',
            'RW',
            'Dusun',
            'Kode Pos',
            'Tanggal Terdaftar',
            'Jumlah Anggota Aktif',
            'Jumlah Anggota Total',
        ];
    }

    public function map($keluarga): array
    {
        return [
            (string) $keluarga->no_kk,
            $keluarga->kepalaKeluarga?->nama_lengkap ?? '-',
            $keluarga->alamat,
            $keluarga->rtRw?->no_rt ?? '-',
            $keluarga->rtRw?->no_rw ?? '-',
            $keluarga->rtRw?->dusun?->nama ?? '-',
            (string) ($keluarga->kode_pos ?? '-'),
            $keluarga->tanggal_terdaftar?->format('d/m/Y') ?? '-',
            $keluarga->penduduk->where('status', 'aktif')->count(),
            $keluarga->penduduk->count(),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function bindValue(Cell $cell, $value): bool
    {
        if (in_array($cell->getColumn(), ['A', 'G'], true)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
