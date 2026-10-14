<?php

namespace Tests\Feature;

use App\Services\Contracts\PdfServiceInterface;
use App\Services\Contracts\QrCodeServiceInterface;
use Tests\TestCase;

class BoilerplateCoreTest extends TestCase
{
    public function test_api_ping_endpoint_returns_standard_json(): void
    {
        $response = $this->getJson('/api/v1/ping');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Pong',
            ])
            ->assertJsonStructure([
                'success',
                'code',
                'message',
                'data' => ['environment', 'timestamp'],
            ]);
    }

    public function test_qr_code_service_generates_svg_and_base64(): void
    {
        $qrService = app(QrCodeServiceInterface::class);

        $svg = $qrService->generateSvg('https://example.com', 150);
        $this->assertStringContainsString('<svg', $svg);

        $base64 = $qrService->generateBase64DataUri('https://example.com', 150);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $base64);
    }

    public function test_pdf_service_renders_binary(): void
    {
        $pdfService = app(PdfServiceInterface::class);

        $binary = $pdfService->renderToBinary('pdf.sample', [
            'title' => 'Test PDF',
            'items' => [
                ['name' => 'Item 1', 'qty' => 1, 'price' => 1000],
            ],
        ]);

        $this->assertNotEmpty($binary);
        $this->assertStringStartsWith('%PDF', $binary);
    }

    public function test_demo_qrcode_route_works(): void
    {
        $response = $this->get('/demo/qrcode');
        $response->assertStatus(200);
        $response->assertSee('QR Code Generator Service Demo');
    }

    public function test_demo_excel_route_works(): void
    {
        $response = $this->get('/demo/excel');
        $response->assertStatus(200);
        $this->assertEquals(
            'attachment; filename=users_export.xlsx',
            $response->headers->get('content-disposition')
        );
    }

    public function test_demo_pdf_route_works(): void
    {
        $response = $this->get('/demo/pdf');
        $response->assertStatus(200);
        $this->assertEquals(
            'attachment; filename=invoice_sample.pdf',
            $response->headers->get('content-disposition')
        );
    }

    public function test_api_404_not_found_returns_standard_error_json(): void
    {
        $response = $this->getJson('/api/v1/non-existent-endpoint');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'code' => 404,
                'message' => 'Resource not found',
            ]);
    }
}
