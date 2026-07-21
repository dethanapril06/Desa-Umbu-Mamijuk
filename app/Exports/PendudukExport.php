<?php

namespace App\Exports;

use App\Models\Penduduk;
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

class PendudukExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithCustomValueBinder, ShouldAutoSize
{
    public function __construct(private readonly array $filters = [])
    {
    }

    public function collection(): Collection
    {
        return Penduduk::with(['keluarga.rtRw.dusun'])
            ->when($this->filters['status'] ?? null, fn (Builder $query, string $value) => $query->where('status', $value))
            ->when($this->filters['jenis_kelamin'] ?? null, fn (Builder $query, string $value) => $query->where('jenis_kelamin', $value))
            ->when($this->filters['pendidikan_terakhir'] ?? null, fn (Builder $query, string $value) => $query->where('pendidikan_terakhir', $value))
            ->when($this->filters['status_perkawinan'] ?? null, fn (Builder $query, string $value) => $query->where('status_perkawinan', $value))
            ->when($this->filters['status_hubungan_keluarga'] ?? null, fn (Builder $query, string $value) => $query->where('status_hubungan_keluarga', $value))
            ->when($this->filters['tanggal_lahir_mulai'] ?? null, fn (Builder $query, string $value) => $query->whereDate('tanggal_lahir', '>=', $value))
            ->when($this->filters['tanggal_lahir_selesai'] ?? null, fn (Builder $query, string $value) => $query->whereDate('tanggal_lahir', '<=', $value))
            ->when($this->filters['rt_rw_id'] ?? null, function (Builder $query, string $value) {
                $query->whereHas('keluarga', fn (Builder $keluarga) => $keluarga->where('rt_rw_id', $value));
            })
            ->when($this->filters['dusun_id'] ?? null, function (Builder $query, string $value) {
                $query->whereHas('keluarga.rtRw', fn (Builder $rtRw) => $rtRw->where('dusun_id', $value));
            })
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Lengkap',
            'No. KK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Pendidikan',
            'Pekerjaan',
            'Status Perkawinan',
            'Hubungan Keluarga',
            'Kewarganegaraan',
            'RT',
            'RW',
            'Dusun',
            'Status Penduduk',
            'No. Telepon',
            'Keterangan',
        ];
    }

    public function map($penduduk): array
    {
        return [
            (string) $penduduk->nik,
            $penduduk->nama_lengkap,
            (string) ($penduduk->keluarga?->no_kk ?? '-'),
            $this->label($penduduk->jenis_kelamin),
            $penduduk->tempat_lahir ?? '-',
            $penduduk->tanggal_lahir?->format('d/m/Y') ?? '-',
            $this->label($penduduk->agama),
            $this->label($penduduk->pendidikan_terakhir),
            $penduduk->pekerjaan ?? '-',
            $this->label($penduduk->status_perkawinan),
            $this->label($penduduk->status_hubungan_keluarga),
            $penduduk->kewarganegaraan,
            $penduduk->keluarga?->rtRw?->no_rt ?? '-',
            $penduduk->keluarga?->rtRw?->no_rw ?? '-',
            $penduduk->keluarga?->rtRw?->dusun?->nama ?? '-',
            $this->label($penduduk->status),
            (string) ($penduduk->no_telepon ?? '-'),
            $penduduk->keterangan ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'Q' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function bindValue(Cell $cell, $value): bool
    {
        if (in_array($cell->getColumn(), ['A', 'C', 'Q'], true)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    private function label(?string $value): string
    {
        return $value ? ucwords(str_replace(['_', '-'], ' ', $value)) : '-';
    }
}
