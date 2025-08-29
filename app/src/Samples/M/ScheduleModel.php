<?php
namespace Samples\M;

class ScheduleModel
{
    private array $schedule = [];
    private string $tz = 'America/Los_Angeles';
    private array $knownCities = ['Los Angeles',
        'Chatsworth', 'West Hills', 'Woodland Hills', 'Canoga Park', 'Winnetka',
        'Northridge', 'Reseda', 'Tarzana', 'Pacific Palisades', 'North Hills',
        'Lake Balboa', 'Encino', 'Brentwood', 'Santa Monica', 'Arleta',
        'Panorama City', 'Van Nuys', 'Valley Glen', 'Sherman Oaks', 'Bel Air',
        'Westwood', 'Sawtelle', 'Mar Vista', 'Venice', 'Playa Vista',
        'Playa Del Rey', 'North Hollywood', 'Valley Village', 'Toluca Lake',
        'Studio City', 'Beverly Crest', 'Hollywood', 'Beverly Hills',
        'Mid-City West', 'Mid-Willshire', 'Warner Center', 'Korea Town',
        'Mid-City', 'West Los Angeles', 'Palms', 'West Adams', 'Crenshaw',
        'Culver City', 'Baldwin Hills', 'View Park', 'Inglewood',
        'Down Town', 'Marina Del Rey'];

    public function loadFromSystemContext(string $systemContext): void
    {
        if (preg_match('/\[SCHEDULE_JSON\](.*?)\[\/SCHEDULE_JSON\]/s', $systemContext, $m)) {
            $json = trim($m[1]);
            $data = json_decode($json, true);
            if (is_array($data)) {
                $this->tz = $data['timezone'] ?? $this->tz;
                $this->schedule = $data['slots'] ?? [];
                $this->knownCities = array_values(array_unique(array_map(
                    fn($s) => mb_strtolower(trim($s['city'] ?? ''), 'UTF-8'),
                    $this->schedule
                )));
            }
        }
    }

    public function getTimezone(): string { return $this->tz; }
    public function getKnownCities(): array { return $this->knownCities; }
    public function getSchedule(): array { return $this->schedule; }

    public function suggestByCity(string $clientCity, ?string $date = null, int $limit = 3): array
    {
        $cityKey = mb_strtolower(trim($clientCity), 'UTF-8');
        $sameCity = [];
        $other = [];

        foreach ($this->schedule as $slot) {
            if ($date && $slot['date'] !== $date) continue;
            $slotCity = mb_strtolower(trim($slot['city'] ?? ''), 'UTF-8');
            if ($slotCity === $cityKey) $sameCity[] = $slot; else $other[] = $slot;
        }

        usort($sameCity, fn($a,$b) => strcmp($a['date'].$a['time'], $b['date'].$b['time']));
        usort($other,    fn($a,$b) => strcmp($a['date'].$a['time'], $b['date'].$b['time']));

        $merged = array_merge($sameCity, $other);
        return array_slice($merged, 0, $limit);
    }

    public function findExact(string $date, string $time, string $city): ?array
    {
        $cityKey = mb_strtolower(trim($city), 'UTF-8');
        foreach ($this->schedule as $s) {
            if ($s['date'] === $date && $s['time'] === $time &&
                mb_strtolower(trim($s['city'] ?? ''), 'UTF-8') === $cityKey) {
                return $s;
            }
        }
        return null;
    }

    public function isSlotAvailable(string $slotId): bool
    {
        foreach ($this->schedule as $s) if ($s['id'] === $slotId) return true;
        return false;
    }
}