<?php
namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class BankHolidayService
{
    // 1. Private fetch method
    private function fetchGovUkBHData(): array
    {
        try {
            /** @var Response $response */
            $response = Http::timeout(10)
                ->retry(2, 200)
                ->get('https://www.gov.uk/bank-holidays.json');

            if ($response->failed()) {
                return [];
            }

            $data = $response->json();

            return is_array($data) ? $data : [];
        } catch (\Throwable $exception) {
            report($exception);

            return [];
        }
    }

    // 2. Private normaliser method
    private function normaliseBankHolidays(array $rawData): array
{
    $events = $rawData['england-and-wales']['events'] ?? [];

    if (!is_array($events)) {
        return [];
    }

    return array_values(array_filter(array_map(function ($event) {
        if (!is_array($event)) {
            return null;
        }

        $date = $event['date'] ?? null;
        $title = $event['title'] ?? null;

        if (!is_string($date) || !is_string($title)) {
            return null;
        }
        
        $cleanTitle = str_ireplace('bank holiday', '', $title);

        $finalTitle = 'Bank Holiday: ' . trim($cleanTitle);

        return [
            'date' => $date,
            'title' => $finalTitle,
            // 'notes' => $event['notes'] ?? null,
        ];
    }, $events)));
}

    // 3. Public orchestrator method
    public function getNormalizedBankHolidays(): array
{
    $rawData = $this->fetchGovUkBHData();

    return $this->normaliseBankHolidays($rawData);
}
}