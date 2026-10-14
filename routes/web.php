<?php

use App\Services\Contracts\ExcelServiceInterface;
use App\Services\Contracts\PdfServiceInterface;
use App\Services\Contracts\QrCodeServiceInterface;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Demo Endpoints demonstrating core boilerplate services
Route::prefix('demo')->group(function () {
    // 1. QR Code Demo
    Route::get('/qrcode', function (QrCodeServiceInterface $qrService) {
        $dataUri = $qrService->generateBase64DataUri('https://github.com', 260);

        return response('<html><body style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;">'
            .'<h2>QR Code Generator Service Demo</h2>'
            .'<img src="'.$dataUri.'" alt="QR Code" style="box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-radius: 8px; padding: 12px; background: white;" />'
            .'<p style="color: #666; margin-top: 12px;">Data: https://github.com</p>'
            .'</body></html>');
    });

    // 2. PDF Export Demo
    Route::get('/pdf', function (PdfServiceInterface $pdfService) {
        $data = [
            'title' => '受領書 / 請求書サンプル',
            'date' => now()->format('Y年m月d日'),
            'customer' => '株式会社オープンゲート',
            'items' => [
                ['name' => 'システム基本設計・要件定義', 'qty' => 1, 'price' => 300000],
                ['name' => 'Laravel バックエンド開発', 'qty' => 1, 'price' => 500000],
                ['name' => 'LINE Bot 連携モジュール', 'qty' => 1, 'price' => 200000],
            ],
        ];

        return $pdfService->download('pdf.sample', $data, 'invoice_sample.pdf');
    });

    // 3. Excel Export Demo
    Route::get('/excel', function (ExcelServiceInterface $excelService) {
        $headings = ['ID', '氏名', 'メールアドレス', '登録日時'];
        $data = [
            [1, '山田 太郎', 'yamada@example.com', '2026-10-01 10:00:00'],
            [2, '佐藤 花子', 'sato@example.com', '2026-10-02 14:30:00'],
            [3, '鈴木 一郎', 'suzuki@example.com', '2026-10-03 09:15:00'],
        ];

        return $excelService->downloadArray($data, $headings, 'users_export.xlsx');
    });
});
