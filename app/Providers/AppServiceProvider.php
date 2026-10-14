<?php

namespace App\Providers;

use App\Services\Contracts\ExcelServiceInterface;
use App\Services\Contracts\MailServiceInterface;
use App\Services\Contracts\PdfServiceInterface;
use App\Services\Contracts\QrCodeServiceInterface;
use App\Services\ExcelService;
use App\Services\MailService;
use App\Services\PdfService;
use App\Services\QrCodeService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MailServiceInterface::class, MailService::class);
        $this->app->singleton(PdfServiceInterface::class, PdfService::class);
        $this->app->singleton(QrCodeServiceInterface::class, QrCodeService::class);
        $this->app->singleton(ExcelServiceInterface::class, ExcelService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
