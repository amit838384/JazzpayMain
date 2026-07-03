<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CafeteriaStudentsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $students;
    protected $parents;
    protected $school;

    public function __construct($students, $parents, $school)
    {
        $this->students = $students;
        $this->parents  = $parents;
        $this->school   = $school;
    }

    public function collection()
    {
        return $this->students->map(function ($row, $index) {
            return [
                'si_no'        => $index + 1,
                'admission_no' => $row->id,
                'student_name' => $row->student_name,
                'parent_name'  => $this->parents[$row->parent_id] ?? '-',
                'school_name'  => $this->school->school_name,
                'grade'        => $row->grade,
                'gender'       => $row->gender,
                'wallet'       => $row->wallet_balance,
                'spend_limit'  => $row->spend_limit,
                'verified'     => $row->verified,
                'status'       => $row->status == 1 ? 'Active' : 'Inactive',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SI No.',
            'Admission No',
            'Student Name',
            'Parent Name',
            'School Name',
            'Grade',
            'Gender',
            'Wallet Balance',
            'Daily Limit',
            'Verified',
            'Status',
        ];
    }

    public function title(): string
    {
        return 'School Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8, 'B' => 14, 'C' => 22, 'D' => 22,
            'E' => 28, 'F' => 10, 'G' => 10,
            'H' => 16, 'I' => 14, 'J' => 10, 'K' => 10,
        ];
    }
}