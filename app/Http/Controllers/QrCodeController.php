<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class QrCodeController extends Controller
{
    public function generateAndSaveQrCodeToExcel()
    {
        // Generate QR Code
        $qrCode = QrCode::create('Contoh data QR code')
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode)->getString();

        // Save QR Code image to temporary file
        $tempImagePath = storage_path('app/public/image/qrcode_temp.png');
        file_put_contents($tempImagePath, $qrCodeImage);

        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Insert QR Code image into Spreadsheet
        $drawing = new Drawing();
        $drawing->setName('QR Code');
        $drawing->setDescription('QR Code');
        $drawing->setPath($tempImagePath);
        $drawing->setHeight(300);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        // Path to store in storage/app/public/excel
        $path = 'excel/qrcode.xlsx';

        // Save Spreadsheet to storage
        $writer = new Xlsx($spreadsheet);
        Storage::disk('public')->put($path, '');
        $writer->save(storage_path('app/public/' . $path));

        // Delete the temporary image
        unlink($tempImagePath);

        // Return success message with public URL
        $url = Storage::url($path);
        return "QR Code berhasil disimpan dalam Excel di storage public. URL: <a href='{$url}' target='_blank'>{$url}</a>";
    }
}
