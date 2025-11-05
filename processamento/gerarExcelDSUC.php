<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('H10', 'TESTE123');
$sheet->getStyle('H10')->getAlignment()->setWrapText(true);

$writer = new Xlsx($spreadsheet);
$writer->save('../DSUC.xlsx');

echo "../DSUC.xlsx";