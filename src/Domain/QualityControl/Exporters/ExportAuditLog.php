<?php

declare(strict_types=1);

namespace Domain\QualityControl\Exporters;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class ExportAuditLog implements FromQuery, WithMapping, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    public function __construct(private int $studyId)
    {
    }

    public function query()
    {
        return Activity::with('causer')->where('study_id', $this->studyId);
    }

    public function headings(): array
    {
        return [
            'event',
            'resource',
            'old',
            'new',
            'user',
            'date',
        ];
    }

    /**
     * @param  Activity  $activity
     * @return array
     */
    public function map($activity): array
    {
        return [
            $activity->event,
            class_basename($activity->subject_type),
            collect($activity->properties->get('old', []))->map(function ($value, $key) {
                return "{$key}: {$value}";
            })->join("\n"),
            collect($activity->properties->get('attributes', []))->map(function ($value, $key) {
                return "{$key}: {$value}";
            })->join("\n"),
            $activity->causer->name,
            Date::dateTimeToExcel($activity->created_at),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            '1' => ['font' => ['bold' => true]],
            'C' => ['alignment' => ['wrapText' => true]],
            'D' => ['alignment' => ['wrapText' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
