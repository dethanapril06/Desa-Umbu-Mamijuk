<?php

namespace App\Exports;

use App\Models\MutasiPenduduk;
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

class MutasiPendudukExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithCustomValueBinder, ShouldAutoSize
{
    public function __construct(private readonly array $filters = [])
    {
    }

    public function collection(): Collection
    {
        return MutasiPenduduk::with(['penduduk.keluarga.rtRw.dusun'])
            ->when($this->filters['jenis_mutasi'] ?? null, fn (Builder $query, string $value) => $query->where('jenis_mutasi', $value))
            ->when($this->filters['tanggal_mutasi_mulai'] ?? null, fn (Builder $query, string $value) => $query->whereDate('tanggal_mutasi', '>=', $value))
            ->when($this->filters['tanggal_mutasi_selesai'] ?? null, fn (Builder $query, string $value) => $query->whereDate('tanggal_mutasi', '<=', $value))
            ->when($this->filters['search'] ?? null, function (Builder $query, string $value) {
                $query->where(function (Builder $searchQuery) use ($value) {
                    $searchQuery->where('no_surat', 'like', "%{$value}%")
                        ->orWhereHas('penduduk', function (Builder $penduduk) use ($value) {
                            $penduduk->where('nama_lengkap', 'like', "%{$value}%")
                                ->orWhere('nik', 'like', "%{$value}%");
                        });
                });
            })
            ->when($this->filters['rt_rw_id'] ?? null, function (Builder $query, string $value) {
                $query->whereHas('penduduk.keluarga', fn (Builder $keluarga) => $keluarga->where('rt_rw_id', $value));
            })
            ->when($this->filters['dusun_id'] ?? null, function (Builder $query, string $value) {
                $query->whereHas('penduduk.keluarga.rtRw', fn (Builder $rtRw) => $rtRw->where('dusun_id', $value));
            })
            ->orderByDesc('tanggal_mutasi')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Penduduk',
            'NIK',
            'No. KK',
            'Jenis Mutasi',
            'Tanggal Mutasi',
            'No. Surat',
            'Alamat Asal',
            'Alamat Tujuan',
            'RT',
            'RW',
            'Dusun',
            'Keterangan',
        ];
    }

    public function map($mutasi): array
    {
        return [
            $mutasi->penduduk?->nama_lengkap ?? '-',
            (string) ($mutasi->penduduk?->nik ?? '-'),
            (string) ($mutasi->penduduk?->keluarga?->no_kk ?? '-'),
            $this->label($mutasi->jenis_mutasi),
            $mutasi->tanggal_mutasi?->format('d/m/Y') ?? '-',
            (string) ($mutasi->no_surat ?? '-'),
            $mutasi->alamat_asal ?? '-',
            $mutasi->alamat_tujuan ?? '-',
            $mutasi->penduduk?->keluarga?->rtRw?->no_rt ?? '-',
            $mutasi->penduduk?->keluarga?->rtRw?->no_rw ?? '-',
            $mutasi->penduduk?->keluarga?->rtRw?->dusun?->nama ?? '-',
            $mutasi->keterangan ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function bindValue(Cell $cell, $value): bool
    {
        if (in_array($cell->getColumn(), ['B', 'C', 'F'], true)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    private function label(?string $value): string
    {
        return $value ? ucwords(str_replace('_', ' ', $value)) : '-';
    }
}
