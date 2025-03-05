<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CreateExcelTemplate extends Command
{
    protected $signature = 'make:excel-template';
    protected $description = 'Create Excel template for voter import';

    public function handle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'prenom');
        $sheet->setCellValue('B1', 'nom');
        $sheet->setCellValue('C1', 'numero_de_carte');

        // Add example data
        $data = [
            ['Amadou', 'Diallo', 'SN2025001'],
            ['Fatou', 'Sow', 'SN2025002'],
            ['Moussa', 'Ndiaye', 'SN2025003'],
        ];

        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item[0]);
            $sheet->setCellValue('B' . $row, $item[1]);
            $sheet->setCellValue('C' . $row, $item[2]);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create writer and save file
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('modele_electeurs.xlsx'));

        $this->info('Excel template created successfully!');
    }
}
