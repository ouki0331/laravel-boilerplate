<?php

namespace App\Services\Contracts;

interface MailServiceInterface
{
    /**
     * Send a template-based email.
     *
     * @param  string|array<string>  $to
     * @param  array<string, mixed>  $data
     * @param  array<string>  $attachments
     */
    public function sendTemplate(string|array $to, string $subject, string $view, array $data = [], array $attachments = []): bool;

    /**
     * Send a plain text email.
     *
     * @param  string|array<string>  $to
     */
    public function sendRaw(string|array $to, string $subject, string $content): bool;
}
