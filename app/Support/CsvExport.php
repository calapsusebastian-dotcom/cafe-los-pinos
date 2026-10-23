<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Streams rows as a CSV that Excel opens with the right accents.
 */
class CsvExport
{
    /**
     * @param  array<int, string>  $headings
     * @param  iterable<array<int, scalar|null>>  $rows
     */
    public static function download(string $filename, array $headings, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headings, $rows): void {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, $headings, ',', '"', '');

            foreach ($rows as $row) {
                fputcsv($handle, array_map(self::safe(...), $row), ',', '"', '');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Cells that start with a formula character are prefixed so spreadsheets
     * never run text typed by a customer.
     */
    private static function safe(mixed $value): mixed
    {
        if (is_string($value) && preg_match('/^[=+\-@\t\r]/', $value) === 1) {
            return "'".$value;
        }

        return $value;
    }
}
