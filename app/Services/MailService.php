<?php

namespace App\Services;

use App\Services\Contracts\MailServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailService implements MailServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function sendTemplate(string|array $to, string $subject, string $view, array $data = [], array $attachments = []): bool
    {
        try {
            Mail::send($view, $data, function ($message) use ($to, $subject, $attachments) {
                $message->to($to)->subject($subject);

                foreach ($attachments as $filePath) {
                    if (file_exists($filePath)) {
                        $message->attach($filePath);
                    }
                }
            });

            return true;
        } catch (Throwable $e) {
            Log::error('MailService sendTemplate failed: '.$e->getMessage(), [
                'to' => $to,
                'subject' => $subject,
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function sendRaw(string|array $to, string $subject, string $content): bool
    {
        try {
            Mail::raw($content, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });

            return true;
        } catch (Throwable $e) {
            Log::error('MailService sendRaw failed: '.$e->getMessage(), [
                'to' => $to,
                'subject' => $subject,
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
