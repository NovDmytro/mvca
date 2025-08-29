<?php
namespace Samples\M;

class InputParserModel
{
    public static function extractPhone(string $text): ?string
    {
        if (preg_match('/(\+1[\s\-\.]?)?(\(?\d{3}\)?[\s\-\.]?)\d{3}[\s\-\.]?\d{4}\b/', $text, $m)) {
            return trim($m[0]);
        }
        return null;
    }

    public static function extractAddress(string $text): ?string
    {
        if (preg_match('/(?:\baddress\b|\baddr\b)\s*[:\-]\s*(.+)$/imu', $text, $m)) {
            return self::cleanupSpaces(self::stripTrailingNonAddress($m[1]));
        }

        $streetTypes = '(?:street|st\.?|avenue|ave\.?|boulevard|blvd\.?|road|rd\.?|drive|dr\.?|lane|ln\.?|court|ct\.?|place|pl\.?|way|terrace|ter\.?|highway|hwy|parkway|pkwy|circle|cir\.?|center|canyon)';
        $unitPart   = '(?:#\s*\w+|unit\s*\w+|apt\.?\s*\w+|suite\s*\w+|fl\s*\w+|floor\s*\w+|bldg\.?\s*\w+|ste\.?\s*\w+)';
        $addrRegex  = '/(\d{1,6}\s+[^\n,]*?\b' . $streetTypes . '\b[^\n,]*' .
            '(?:\s*,\s*(?:' . $unitPart . '|[A-Za-z0-9\.\-# ]+)){0,3})/iu';

        if (preg_match($addrRegex, $text, $m)) {
            $addr = self::cleanupSpaces(self::stripTrailingNonAddress($m[1]));
            return $addr !== '' ? $addr : null;
        }

        $lines = array_filter(array_map('trim', preg_split("/\R/u", $text)));
        foreach ($lines as $line) {
            if (preg_match('/^\s*\d{1,6}\s+.+/u', $line)) {
                return self::cleanupSpaces(self::stripTrailingNonAddress($line));
            }
        }

        return null;
    }

    private static function stripTrailingNonAddress(string $s): string
    {
        $s = preg_replace('/[,;]\s*(today|tomorrow|mon(day)?|tue(sday)?|wed(nesday)?|thu(rsday)?|fri(day)?|sat(urday)?|sun(day)?)\b.*$/iu', '', $s);
        $s = preg_replace('/\s*(please|thanks|thank you|tmrw|tnx)\b.*$/iu', '', $s);
        return trim($s, " \t\n\r\0\x0B,;");
    }

    private static function cleanupSpaces(string $s): string
    {
        $s = preg_replace('/\s+/u', ' ', $s);
        return trim($s);
    }

    public static function extractCityFromAddress(string $address, array $knownCities): ?string
    {
        if ($address === '') return null;

        [$cityIndex, $orderedKeys] = self::buildCityIndex($knownCities);

        $segments = array_map('trim', explode(',', $address));
        for ($i = count($segments) - 1; $i >= 0; $i--) {
            $seg = $segments[$i];
            if ($seg === '') continue;

            if (preg_match('/^\s*(ca|california)\b/i', $seg)) continue;
            if (preg_match('/\b\d{5}(-\d{4})?$/', $seg)) continue;

            $segNorm = self::norm($seg);
            $segNorm = self::applySynonyms($segNorm, $cityIndex);

            foreach ($orderedKeys as $key) {
                if (preg_match('/(?<=^|\s)'.preg_quote($key,'/').'(?=$|\s)/u', $segNorm)) {
                    return $cityIndex[$key];
                }
            }
        }

        $addrNorm = self::applySynonyms(self::norm($address), $cityIndex);
        foreach ($orderedKeys as $key) {
            if (preg_match('/(?<=^|\s)'.preg_quote($key,'/').'(?:\s|$)/u', $addrNorm)) {
                return $cityIndex[$key];
            }
        }

        return null;
    }

    private static function buildCityIndex(array $knownCities): array
    {
        $canon = [];
        foreach ($knownCities as $c) {
            $c = trim($c);
            if ($c === '') continue;
            $canon[$c] = true;
        }
        $canon = array_keys($canon);

        $index = [];
        foreach ($canon as $c) {
            $norm = self::norm($c);
            if ($norm !== '') $index[$norm] = $c;
        }

        $syn = self::citySynonyms();
        foreach ($syn as $variant => $target) {
            $tNorm = self::norm($target);
            if (isset($index[$tNorm])) {
                $index[self::norm($variant)] = $index[$tNorm]; // variant → canonical
            }
        }

        $keys = array_keys($index);
        usort($keys, fn($a,$b) => mb_strlen($b,'UTF-8') <=> mb_strlen($a,'UTF-8'));

        return [$index, $keys];
    }

    private static function norm(string $s): string
    {
        $s = mb_strtolower($s, 'UTF-8');
        $s = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $s);
        $s = preg_replace('/\s+/u', ' ', $s);
        return trim($s);
    }

    private static function applySynonyms(string $normText, array $cityIndex): string
    {
        $active = [];
        $syn = self::citySynonyms();
        foreach ($syn as $variant => $target) {
            $tNorm = self::norm($target);
            if (isset($cityIndex[$tNorm])) {
                $active[self::norm($variant)] = $tNorm;
            }
        }
        if (!$active) return $normText;

        foreach ($active as $from => $to) {
            $normText = preg_replace('/(?<=^|\s)'.preg_quote($from,'/').'(?=$|\s)/u', $to, $normText);
        }
        return $normText;
    }
    public static function extractCityStandalone(string $text, array $knownCities): ?string
    {
        [$cityIndex, $orderedKeys] = self::buildCityIndex($knownCities);

        $norm = self::applySynonyms(self::norm($text), $cityIndex);

        foreach ($orderedKeys as $key) {
            if (preg_match('/(^|\s)'.preg_quote($key,'/').'(?=\s|$)/u', $norm)) {
                return $cityIndex[$key];
            }
        }
        return null;
    }
    private static function citySynonyms(): array
    {
        return [
            // базовые
            'la' => 'Los Angeles',
            'l a' => 'Los Angeles',
            'l.a' => 'Los Angeles',
            'losangeles' => 'Los Angeles',
            'los-angeles' => 'Los Angeles',

            // downtown / dtla
            'dtla' => 'Down Town',
            'downtown' => 'Down Town',
            'down town' => 'Down Town',

            // koreatown
            'koreatown' => 'Korea Town',
            'k town'    => 'Korea Town',
            'k-town'    => 'Korea Town',

            // mid-(wil)shire
            'mid wilshire'   => 'Mid-Willshire',
            'mid-wilshire'   => 'Mid-Willshire',
            'midwillshire'   => 'Mid-Willshire',
            'mid willshire'  => 'Mid-Willshire',

            // west la → west los angeles
            'west la' => 'West Los Angeles',

            // noho → north hollywood
            'noho' => 'North Hollywood',
            'n hollywood' => 'North Hollywood',
            'n. hollywood' => 'North Hollywood',

            // valley village/van nuys/valley glen — часто слитно
            'vannuys'     => 'Van Nuys',
            'valleyglen'  => 'Valley Glen',
            'studiocity'  => 'Studio City',
            'tolucalake'  => 'Toluca Lake',
            'pacificpalisades' => 'Pacific Palisades',

            // marina del rey
            'mdr' => 'Marina Del Rey',

            // playa del rey / playa vista
            'playadelrey' => 'Playa Del Rey',
            'playavista'  => 'Playa Vista',

            // santa monica
            'santamonica' => 'Santa Monica',

            // westwood/sawtelle слитно
            'westwood' => 'Westwood',
            'sawtelle' => 'Sawtelle',

            // beverly crest/west adams и т.п. слитно
            'beverlycrest' => 'Beverly Crest',
            'westadams'    => 'West Adams',
            'crenshaw'     => 'Crenshaw',
            'culvercity'   => 'Culver City',
            'viewpark'     => 'View Park',
            'baldwinhills' => 'Baldwin Hills',
            'hermosabeach' => 'Hermosa Beach',
            'woodlandhills'=> 'Woodland Hills',
            'canogapark'   => 'Canoga Park',
            'northhills'   => 'North Hills',
            'lakebalboa'   => 'Lake Balboa',
            'warnercenter' => 'Warner Center',
            'westhills'    => 'West Hills',
            'valleyvillage'=> 'Valley Village',
            'mid city west'=> 'Mid-City West',
            'mid city'     => 'Mid-City'
        ];
    }

    private static function titleCaseCity(string $cityKey): string
    {
        return implode(' ', array_map(
            fn($w) => mb_convert_case($w, MB_CASE_TITLE, 'UTF-8'),
            preg_split('/\s+/', $cityKey, -1, PREG_SPLIT_NO_EMPTY)
        ));
    }


    public static function parseRelativeTime(string $text, string $tz): ?array
    {
        $text = mb_strtolower($text, 'UTF-8');

        $dayToken = null;
        if (preg_match('/\b(today|tomorrow|mon(day)?|tue(s|sday)?|wed(nesday)?|thu(r|rs|rsday)?|fri(day)?|sat(urday)?|sun(day)?)\b/i', $text, $m)) {
            $dayToken = strtolower($m[1]);
            // нормируем короткие
            $map = [
                'mon' => 'monday','tue'=>'tuesday','tues'=>'tuesday','wed'=>'wednesday',
                'thu'=>'thursday','thur'=>'thursday','thurs'=>'thursday',
                'fri'=>'friday','sat'=>'saturday','sun'=>'sunday'
            ];
            $dayToken = $map[$dayToken] ?? $dayToken;
        }

        // 5 pm / 5pm / 5:30 pm / 17:30
        $hour = null; $min = 0; $ampm = null;
        if (preg_match('/\b(\d{1,2})(?::(\d{2}))?\s*(am|pm)?\b/i', $text, $m)) {
            $hour = (int)$m[1];
            $min  = isset($m[2]) ? (int)$m[2] : 0;
            $ampm = isset($m[3]) ? strtolower($m[3]) : null;
            if ($ampm === 'pm' && $hour < 12) $hour += 12;
            if ($ampm === 'am' && $hour === 12) $hour = 0;
        }
        if ($dayToken === null || $hour === null) return null;

        $tzObj = new \DateTimeZone($tz);
        $now   = new \DateTimeImmutable('now', $tzObj);

        $base = match ($dayToken) {
            'today'    => $now,
            'tomorrow' => $now->modify('+1 day'),
            default    => self::nextWeekday($now, $dayToken)
        };

        $dt = new \DateTimeImmutable($base->format('Y-m-d') . sprintf(' %02d:%02d:00', $hour, $min), $tzObj);
        return [
            'date' => $dt->format('Y-m-d'),
            'time' => $dt->format('H:i'),
            'unix' => $dt->getTimestamp()
        ];
    }

    private static function nextWeekday(\DateTimeImmutable $from, string $weekday): \DateTimeImmutable
    {
        $map = [
            'monday'=>1,'tuesday'=>2,'wednesday'=>3,'thursday'=>4,'friday'=>5,'saturday'=>6,'sunday'=>7
        ];
        $target = $map[$weekday] ?? null;
        if (!$target) return $from;
        $w = (int)$from->format('N');
        $delta = $target - $w;
        if ($delta <= 0) $delta += 7;
        return $from->modify("+{$delta} days");
    }
}