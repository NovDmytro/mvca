<?php

namespace Samples\C;

use Engine\Config;
use Engine\Output;
use Services\Request;
use Services\WebSocket;
use Samples\M;


class AIController
{
    private Config $config;
    private Output $output;
    private M\AIModel $aIModel;
    private M\ScheduleModel $scheduleModel;
    private M\InputParserModel $inputParserModel;
    private array $sessions = [];


    public function __construct(
        Config   $config,
        Output   $output,
        M\AIModel $aIModel,
        M\ScheduleModel $scheduleModel,
        M\InputParserModel $inputParserModel,

    )
    {
        $this->config = $config;
        $this->output = $output;
        $this->aIModel = $aIModel;
        $this->scheduleModel = $scheduleModel;
        $this->inputParserModel = $inputParserModel;
    }

    public function worker(): void
    {
        // WebSocket('listenTime','lag') listenTime in seconds, lag in microseconds
        $webSocket=new WebSocket('600','100000');

        // Triggers when someone connected
        // $clientSocket is a socket resource id
        // $clientData is clients data array with headers,peer and cookies keys
        $webSocket->onConnect(function ($clientSocket, $clientData) use ($webSocket){
            //$clientSocket - socket resource id, $clientData - optional data, id is custom id, optional
            $newId=$webSocket->addClient($clientSocket,$clientData);
            // сессия для клиента
            $this->sessions[$newId] = [
                'address' => null,
                'city'    => null,
                'phone'   => null,
                'time'    => null,
                'time_unix' => null,
                'prev_response_id' => null,
                'booking_done' => false,
            ];



            //Send message to new client
            $webSocket->send($newId, json_encode(['data' => 'Hello, ' . $clientData['peer'], 'type' => 'HELO']));

            //Broadcast message to rest clients
            foreach ($webSocket->getClients() as $id=>$client) {
                if ($id != $newId) {
                $webSocket->send($id, json_encode(['data' => 'Client ' . $clientData['peer'] . ' connected', 'type' => 'HELO']));//id data
            }
            }
/*
 * AI SCHEDULER HEADER ->
 * set context (this is concept project, data is for tests, no real database/data yet.)
 */
            $this->aIModel->setContext('
            You are TV Mounting service provider. You need to talk with client to schedule appointment for him. You are mounting TVs every day from 8am to 10pm.
            Here is scheduled(not available) times:            
            [SCHEDULE_JSON]
{
  "timezone": "America/Los_Angeles",
  "slots": [ { "id": "slot_001", "date": "2025-09-02", "time": "14:00", "city": "Inglewood", "worker_id": "w1", "address": "3500 West Manchester Boulevard, Unit 459, Inglewood" }, { "id": "slot_002", "date": "2025-09-02", "time": "16:00", "city": "Santa Monica", "worker_id": "w1", "address": "914 Lincoln Boulevard, #108, Santa Monica, CA 90403" }, { "id": "slot_003", "date": "2025-09-02", "time": "10:00", "city": "Los Angeles", "worker_id": "w2", "address": "5901 Center Drive, Los Angeles" }, { "id": "slot_004", "date": "2025-09-03", "time": "13:00", "city": "Burbank", "worker_id": "w1", "address": "1736 North Ontario Street, Burbank" }, { "id": "slot_005", "date": "2025-09-03", "time": "11:00", "city": "Hermosa Beach", "worker_id": "w2", "address": "222 2nd Street, Hermosa Beach" }, { "id": "slot_006", "date": "2025-09-03", "time": "09:00", "city": "Los Angeles", "worker_id": "w1", "address": "19210 Sylvan Street, Los Angeles" }, { "id": "slot_007", "date": "2025-09-03", "time": "15:00", "city": "Los Angeles", "worker_id": "w2", "address": "6253 Lankershim Boulevard, Los Angeles" } ]
}
[/SCHEDULE_JSON]

GIVE SHORT ANSWERS, LIKE YOU ARE REAL PERSON
        ');
            /*
             * <- AI SCHEDULER HEADER
             */
        });

        // Triggers when someone send message to socket
        $webSocket->onMessage(function ($clientId, $message) use ($webSocket) {
            $message = json_decode($message);
            $webSocket->send($clientId, json_encode(['data' => $message->name . '[OUT]: ' . $message->data, 'type' => 'DATA']));
            $aIResponse = $this->handleClientMessage($clientId, $message->data);
            $webSocket->send($clientId, json_encode(['data' => 'AI[IN]: ' . $aIResponse['reply'], 'type' => 'DATA']));



        });

        // To avoid flood better to include some action before send (onTick will do something on each tick
        $webSocket->onTick(function () use ($webSocket) {
        // Your code
        });

        // Triggers when someone exit
        $webSocket->onClose(function ($clientId) use ($webSocket) {
            foreach ($webSocket->getClients() as $id=>$client) {
                $webSocket->send($id,json_encode(['data' => 'See you '.$clientId, 'type' => 'EACH']));
            }
        });

        // Triggers before stop listening
        $webSocket->onStop(function () use ($webSocket) {
            foreach ($webSocket->getClients() as $id=>$client) {
                $webSocket->send($id,json_encode(['data' => 'Good bye everyone, socket is closing.', 'type' => 'STOP']));
            }
        });

        //Start listen loop
        $webSocket->listen(8081); //port host
    }

    public function main(): void
    {

        $view['title'] = '{{AI Schedule sample}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/AI", $view);
    }
    public function handleClientMessage(int $clientId, string $clientText): array
    {
        $defaults = [
            'address'          => null,
            'city'             => null,
            'phone'            => null,
            'time'             => null,        // "YYYY-MM-DD HH:MM"
            'time_unix'        => null,
            'time_window'      => null,        // ['date','start','end']
            'last_proposed'    => [],
            'prev_response_id' => null,
            'booking_done'     => false,
            'pending_exact'    => null,        // ['date','time','unix','city']
        ];
        $this->sessions[$clientId] = array_replace($defaults, $this->sessions[$clientId] ?? []);
        $sess = &$this->sessions[$clientId];

        $this->scheduleModel->loadFromSystemContext($this->aIModel->getSystemContext());
        $tz          = $this->scheduleModel->getTimezone();
        $knownCities = $this->scheduleModel->getKnownCities();

        $phone   = $this->inputParserModel->extractPhone($clientText);
        $address = $this->inputParserModel->extractAddress($clientText);

        $city = null;
        if ($address) {
            $city = $this->inputParserModel->extractCityFromAddress($address, $knownCities);
        }
        if (!$city && method_exists($this->inputParserModel, 'extractCityStandalone')) {
            $city = $this->inputParserModel->extractCityStandalone($clientText, $knownCities);
        }

        $timeSpec   = $this->inputParserModel->parseRelativeTime($clientText, $tz);                         // ['date','time','unix']|null
        $timeAbs    = method_exists($this, 'parseAbsoluteDateTime') ? $this->parseAbsoluteDateTime($clientText, $tz) : null;
        $timeWindow = method_exists($this, 'parseTimeWindow')       ? $this->parseTimeWindow($clientText, $tz)       : null;

        $needsAiNorm = (
            (!$timeSpec && !$timeAbs && !$timeWindow) ||
            (bool)preg_match('/\bany\s*time\b|\banytime\b|\bafter\s*lunch\b|\blate\b|\bearly\b/i', $clientText)
        );
        if ($needsAiNorm && method_exists($this, 'normalizeWithAI')) {
            $ai = $this->normalizeWithAI($clientText, $tz, $knownCities);
            if (!empty($ai['city']) && empty($city)) $city = $ai['city'];

            if (!empty($ai['time_exact'])) {
                $date = $ai['date'] ?? ($timeSpec['date'] ?? ($timeAbs['date'] ?? ($timeWindow['date'] ?? null)));
                if ($date) {
                    $timeSpec = [
                        'date' => $date,
                        'time' => $ai['time_exact'],
                        'unix' => $this->toUnix($date, $ai['time_exact'], $tz)
                    ];
                }
            } elseif (!empty($ai['time_window']['start']) && !empty($ai['time_window']['end'])) {
                $wdate = $ai['time_window']['date'] ?? ($timeSpec['date'] ?? ($timeAbs['date'] ?? ($timeWindow['date'] ?? null)));
                if ($wdate) {
                    $timeWindow = [
                        'date'  => $wdate,
                        'start' => $ai['time_window']['start'],
                        'end'   => $ai['time_window']['end'],
                    ];
                }
            }
        }

        if ($phone)   $sess['phone']   = $phone;
        if ($address) $sess['address'] = $address;
        if ($city)    $sess['city']    = $city;

        if ($timeSpec) {
            $sess['time']        = $timeSpec['date'].' '.$timeSpec['time'];
            $sess['time_unix']   = $timeSpec['unix'];
            $sess['time_window'] = null;
        }
        if ($timeAbs) {
            $sess['time']        = $timeAbs['date'].' '.$timeAbs['time'];
            $sess['time_unix']   = $timeAbs['unix'];
            $sess['time_window'] = null;
        }
        if ($timeWindow) {
            $start = $timeWindow['start'];
            if ($start < '08:00' && preg_match('/^\d{1,2}:\d{2}$/', $start)) {
                $h = (int)substr($start,0,2);
                if ($h>=1 && $h<=7) { $h += 12; $start = sprintf('%02d:%02d', $h, (int)substr($start,3,2)); }
                $timeWindow['start'] = $start;
            }
            $sess['time']        = $timeWindow['date'].' '.$timeWindow['start'];
            $sess['time_unix']   = $this->toUnix($timeWindow['date'], $timeWindow['start'], $tz);
            $sess['time_window'] = $timeWindow;
        }

        $busy = $this->getBusySlotsSafe();

        if (!empty($sess['last_proposed']) && is_array($sess['last_proposed'])) {
            $picked = $this->tryPickFromReply($clientText, $sess['last_proposed'], $tz);
            if ($picked) {
                $sess['time']        = $picked['date'].' '.$picked['time'];
                $sess['time_unix']   = $picked['unix'];
                $sess['city']        = $picked['city'];
                $sess['picked_slot'] = $picked;
                $sess['pending_exact'] = [
                    'date' => $picked['date'],
                    'time' => $picked['time'],
                    'unix' => $picked['unix'],
                    'city' => $picked['city'],
                ];

                $need = [];
                if (empty($sess['phone']))   $need[] = 'phone number';
                if (empty($sess['address'])) $need[] = 'address';

                if (!empty($need)) {
                    return [
                        'status'     => 'ask_more',
                        'reply'      => 'To finalize, please provide: '.implode(' and ', $need).'.',
                        'responseId' => $sess['prev_response_id'] ?? null
                    ];
                }

                $workerId  = $picked['worker_id'] ?? ($this->pickBestWorker($busy, $sess['city'], $picked['date'], $picked['time'], $tz) ?? 'w1');
                $bookingId = '1';
                $confirm   = $this->makeFinalConfirmation($sess, $tz);

                $sess['booking_done']  = true;
                unset($sess['last_proposed'], $sess['pending_exact']);

                return [
                    'status'  => 'booked',
                    'booking' => [
                        'id'   => $bookingId,
                        'slot' => ['date'=>$picked['date'],'time'=>$picked['time'],'city'=>$picked['city'],'worker_id'=>$workerId],
                        'unix' => $sess['time_unix']
                    ],
                    'reply'   => $confirm." Booking ID: #{$bookingId}.",
                    'responseId' => $sess['prev_response_id'] ?? null
                ];
            }
        }

        if (!empty($timeSpec) && !empty($sess['city'])) {
            $datePart = $timeSpec['date'];
            $timePart = $timeSpec['time'];
            $isBusy   = (bool)$this->scheduleModel->findExact($datePart, $timePart, $sess['city']);

            if (!$isBusy) {
                $sess['pending_exact'] = [
                    'date' => $datePart,
                    'time' => $timePart,
                    'unix' => $timeSpec['unix'],
                    'city' => $sess['city'],
                ];
                $sess['time']      = $datePart.' '.$timePart;
                $sess['time_unix'] = $timeSpec['unix'];

                $missing = [];
                if (empty($sess['phone']))   $missing[] = 'phone number';
                if (empty($sess['address'])) $missing[] = 'address';

                if ($missing) {
                    $slotUs = $this->formatUsSlot($datePart, $timePart, $tz);
                    return [
                        'status'     => 'ask_more',
                        'reply'      => "I can do {$slotUs} in {$sess['city']}. Please share your ".implode(' and ', $missing)." to confirm.",
                        'responseId' => $sess['prev_response_id'] ?? null
                    ];
                }

                $workerId  = $this->pickBestWorker($busy, $sess['city'], $datePart, $timePart, $tz) ?? 'w1';
                $bookingId = '1';
                $confirm   = $this->makeFinalConfirmation($sess, $tz);

                $sess['booking_done'] = true;
                unset($sess['pending_exact']);

                return [
                    'status'  => 'booked',
                    'booking' => [
                        'id'   => $bookingId,
                        'slot' => ['date'=>$datePart,'time'=>$timePart,'city'=>$sess['city'],'worker_id'=>$workerId],
                        'unix' => $sess['time_unix']
                    ],
                    'reply'   => $confirm." Booking ID: #{$bookingId}.",
                    'responseId' => $sess['prev_response_id'] ?? null
                ];
            }

            $window = [
                'date'  => $datePart,
                'start' => $this->minusHour($timePart, 1),
                'end'   => $this->plusHour($timePart, 2),
            ];
            $candidates = $this->proposeFreeNearBusy($busy, $sess['city'], $datePart, $timePart, $window, $tz, 3);
            if (!empty($candidates)) {
                $sess['last_proposed'] = $candidates;
                $list = array_map(fn($c) => $this->formatUsSlot($c['date'], $c['time'], $tz), $candidates);
                return [
                    'status'   => 'proposed',
                    'proposed' => $candidates,
                    'reply'    => "That time is booked, but near it I can do: ".implode(', ', $list).". Which works for you?",
                    'responseId' => $sess['prev_response_id'] ?? null
                ];
            }

            return [
                'status'     => 'ask_more',
                'reply'      => "That time is booked. Could you share another time window (e.g., 3–7pm)?",
                'responseId' => $sess['prev_response_id'] ?? null
            ];
        }

        if (!empty($sess['phone']) && !empty($sess['address']) && !empty($sess['city']) && (!empty($sess['time_unix']) || !empty($sess['time_window']))) {
            if (!empty($sess['pending_exact'])) {
                $sess['time']      = $sess['pending_exact']['date'].' '.$sess['pending_exact']['time'];
                $sess['time_unix'] = $sess['pending_exact']['unix'];
                if (empty($sess['city']) && !empty($sess['pending_exact']['city'])) {
                    $sess['city'] = $sess['pending_exact']['city'];
                }
            }

            $datePart = null; $timePart = null;
            if (!empty($sess['time'])) {
                [$datePart, $timePart] = explode(' ', $sess['time'], 2);
            }
            $hasExactTime = ($datePart && $timePart);

            $conflict = false;
            if ($hasExactTime) {
                $conflict = (bool)$this->scheduleModel->findExact($datePart, $timePart, $sess['city']);
            }

            if (!$conflict && $hasExactTime) {
                $workerId  = $this->pickBestWorker($busy, $sess['city'], $datePart, $timePart, $tz) ?? 'w1';
                $bookingId = '1';
                $confirm   = $this->makeFinalConfirmation($sess, $tz);

                $sess['booking_done'] = true;
                unset($sess['pending_exact']);

                return [
                    'status'  => 'booked',
                    'booking' => [
                        'id'   => $bookingId,
                        'slot' => ['date'=>$datePart,'time'=>$timePart,'city'=>$sess['city'],'worker_id'=>$workerId],
                        'unix' => $sess['time_unix']
                    ],
                    'reply'   => $confirm." Booking ID: #{$bookingId}.",
                    'responseId' => $sess['prev_response_id'] ?? null
                ];
            }

            $dateFilter = $hasExactTime ? $datePart : ($sess['time_window']['date'] ?? null);
            $prefTime   = $hasExactTime ? $timePart : ($sess['time_window']['start'] ?? null);
            $window     = $sess['time_window'] ?? null;

            $candidates = $this->proposeFreeNearBusy($busy, $sess['city'], $dateFilter, $prefTime, $window, $tz, 3);
            if (!empty($candidates)) {
                $sess['last_proposed'] = $candidates;

                $list = array_map(fn($c) => $this->formatUsSlot($c['date'], $c['time'], $tz)." — {$c['city']}", $candidates);
                $needPhone = empty($sess['phone']) ? " (also provide your phone number if not yet)" : "";
                return [
                    'status'   => 'proposed',
                    'proposed' => $candidates,
                    'reply'    => "If it helps, a couple times in {$sess['city']}: ".implode('; ', $list).". Tell me which one works for you{$needPhone}.",
                    'responseId' => $sess['prev_response_id'] ?? null
                ];
            }

            return [
                'status'     => 'ask_more',
                'reply'      => "No nearby availability in {$sess['city']}. Please suggest another time window (e.g., tomorrow 3–7pm).",
                'responseId' => $sess['prev_response_id'] ?? null
            ];
        }

        if (!empty($sess['city'])) {
            $hasTimePref     = !empty($sess['time_unix']) || !empty($sess['time_window']);
            $askedForOptions = $this->isOptionsRequest($clientText);

            if (!$hasTimePref && !$askedForOptions) {
                $need = [];
                if (empty($sess['phone']))   $need[] = 'phone number';
                if (empty($sess['address'])) $need[] = 'address';
                $extra = $need ? " (and your ".implode(' and ', $need).")" : "";
                return [
                    'status'     => 'ask_more',
                    'reply'      => "Got it in {$sess['city']}. What time works (e.g., today 5pm or a window like 3–7pm){$extra}?",
                    'responseId' => $sess['prev_response_id'] ?? null
                ];
            }

            $dateFilter = !empty($sess['time']) ? explode(' ', $sess['time'], 2)[0] : ($sess['time_window']['date'] ?? null);
            $prefTime   = !empty($sess['time']) ? (explode(' ', $sess['time'], 2)[1] ?? null) : ($sess['time_window']['start'] ?? null);
            $window     = $sess['time_window'] ?? null;

            $candidates = $this->proposeFreeNearBusy($busy, $sess['city'], $dateFilter, $prefTime, $window, $tz, 3);
            if (!empty($candidates)) {
                if (!empty($sess['last_proposed']) && $this->sameProposals($sess['last_proposed'], $candidates) && !$askedForOptions) {
                    $times = array_values(array_unique(array_map(fn($c) => $this->formatUsTime($c['time']), $candidates)));
                    $timesLine = implode(' or ', $times); // "9:00 AM or 10:00 AM"
                    $tail      = empty($sess['phone']) ? " (share your phone number too if you haven’t yet)" : "";
                    return [
                        'status'     => 'ask_more',
                        'reply'      => "We can do {$sess['city']} around {$timesLine}. Would you like me to list full options or hold one{$tail}?",
                        'responseId' => $sess['prev_response_id'] ?? null
                    ];
                }

                $sess['last_proposed'] = $candidates;
                $list = array_map(fn($c) => $this->formatUsSlot($c['date'], $c['time'], $tz), $candidates);
                $need = [];
                if (empty($sess['phone']))   $need[] = 'phone number';
                if (empty($sess['address'])) $need[] = 'address';
                $tail = $need ? " (and your ".implode(' and ', $need)." to finalize)" : "";

                return [
                    'status'   => 'proposed',
                    'proposed' => $candidates,
                    'reply'    => "If it helps, a couple times in {$sess['city']}: ".implode(', ', $list).". Let me know which works for you{$tail}.",
                    'responseId' => $sess['prev_response_id'] ?? null
                ];
            }

            return [
                'status'     => 'ask_more',
                'reply'      => "I don’t see nearby availability in {$sess['city']}. Could you share another time window (e.g., tomorrow 3–7pm)?",
                'responseId' => $sess['prev_response_id'] ?? null
            ];
        }

        $need = [];
        if (empty($sess['address']))   $need[] = 'address';
        if (empty($sess['phone']))     $need[] = 'phone number';
        if (empty($sess['city']))      $need[] = 'city (as in the address)';
        if (empty($sess['time_unix']) && empty($sess['time_window'])) $need[] = 'time (e.g., today 5pm) or a time window (e.g., tomorrow 3–7pm)';

        return [
            'status'     => 'ask_more',
            'reply'      => 'Please provide: '.implode(', ', $need).'.',
            'responseId' => $sess['prev_response_id'] ?? null
        ];
    }



    private function isOptionsRequest(string $text): bool
    {
        $t = mb_strtolower($text, 'UTF-8');
        return (bool)preg_match(
            '/\b(slot|slots|availability|available|free|options?)\b|
          \b(do you have|any availability|what time|when can|what slots|show (me )?slots?)\b/ix',
            $t
        );
    }

    private function sameProposals(array $a, array $b): bool
    {
        $norm = function(array $arr) {
            return array_map(
                fn($x) => ($x['date']??'').'|'.($x['time']??'').'|'.mb_strtolower($x['city']??'', 'UTF-8'),
                $arr
            );
        };
        $na = $norm($a); sort($na);
        $nb = $norm($b); sort($nb);
        return $na === $nb;
    }

    private function plusHour(string $time, int $h=1): string {
        [$H,$M]=array_map('intval', explode(':',$time));
        $H = min(22, $H + $h);
        return sprintf('%02d:%02d', $H, $M);
    }
    private function minusHour(string $time, int $h=1): string {
        [$H,$M]=array_map('intval', explode(':',$time));
        $H = max(8, $H - $h);
        return sprintf('%02d:%02d', $H, $M);
    }


    private function parseAbsoluteDateTime(string $text, string $tz): ?array
    {
        if (preg_match('/\b(\d{4})-(\d{2})-(\d{2})[ T](\d{1,2}):(\d{2})\b/', $text, $m)) {
            $y=(int)$m[1]; $mo=(int)$m[2]; $d=(int)$m[3];
            $h=(int)$m[4]; $mi=(int)$m[5];
            if ($h>=0 && $h<=23 && $mi>=0 && $mi<=59) {
                $date = sprintf('%04d-%02d-%02d',$y,$mo,$d);
                $time = sprintf('%02d:%02d',$h,$mi);
                return [
                    'date' => $date,
                    'time' => $time,
                    'unix' => $this->toUnix($date,$time,$tz)
                ];
            }
        }
        return null;
    }

    private function tryPickFromReply(string $text, array $proposed, string $tz): ?array
    {
        $txt = mb_strtolower(trim($text), 'UTF-8');

        $word2idx = [
            '1'=>0,'first'=>0,'one'=>0,
            '2'=>1,'second'=>1,'two'=>1,
            '3'=>2,'third'=>2,'three'=>2,
        ];
        if (isset($word2idx[$txt]) && isset($proposed[$word2idx[$txt]])) {
            return $proposed[$word2idx[$txt]];
        }

        if (preg_match('/\b(\d{4}-\d{2}-\d{2})[ T](\d{1,2}):(\d{2})\b/', $txt, $m)) {
            $date = $m[1];
            $time = sprintf('%02d:%02d', (int)$m[2], (int)$m[3]);
            foreach ($proposed as $p) {
                if ($p['date']===$date && $p['time']===$time) return $p;
            }
        }

        if (preg_match('/\b(\d{1,2}):(\d{2})\b/', $txt, $m)) {
            $time = sprintf('%02d:%02d',(int)$m[1],(int)$m[2]);
            $cands = array_values(array_filter($proposed, fn($p)=>$p['time']===$time));
            if (count($cands)===1) return $cands[0];
        }

        if (preg_match('/\b(\d{1,2})\s*(am|pm)\b/', $txt, $m)) {
            $h=(int)$m[1]; $ampm=$m[2];
            if ($ampm==='pm' && $h<12) $h+=12;
            if ($ampm==='am' && $h===12) $h=0;
            $time = sprintf('%02d:00', $h);
            $cands = array_values(array_filter($proposed, fn($p)=>$p['time']===$time));
            if (count($cands)===1) return $cands[0];
        }

        return null;
    }



    private function parseTimeWindow(string $text, string $tz): ?array
    {
        $txt = mb_strtolower($text, 'UTF-8');

        $dayToken = null;
        if (preg_match('/\b(today|tomorrow|mon(day)?|tue(s|sday)?|wed(nesday)?|thu(r|rs|rsday)?|fri(day)?|sat(urday)?|sun(day)?)\b/i', $txt, $m)) {
            $dayToken = strtolower($m[1]);
            $map = ['mon'=>'monday','tue'=>'tuesday','tues'=>'tuesday','wed'=>'wednesday',
                'thu'=>'thursday','thur'=>'thursday','thurs'=>'thursday',
                'fri'=>'friday','sat'=>'saturday','sun'=>'sunday'];
            $dayToken = $map[$dayToken] ?? $dayToken;
        } else {
            return null;
        }

        $quick = null;
        if (preg_match('/\bany\s*time\b|\banytime\b/i', $txt))        $quick = ['08:00','22:00'];
        elseif (preg_match('/\bmorning\b/i', $txt))                   $quick = ['09:00','12:00'];
        elseif (preg_match('/\bearly\s*afternoon\b/i', $txt))         $quick = ['12:00','15:00'];
        elseif (preg_match('/\bafternoon\b/i', $txt))                 $quick = ['12:00','17:00'];
        elseif (preg_match('/\blate\s*afternoon\b/i', $txt))          $quick = ['15:00','18:00'];
        elseif (preg_match('/\bevening\b/i', $txt))                   $quick = ['17:00','20:00'];
        elseif (preg_match('/\btonight\b/i', $txt))                   $quick = ['18:00','22:00'];

        $dash = '[\x{2012}\x{2013}\x{2014}\-]'; // figure/en/em dash или дефис
        $re = '/\b(\d{1,2})(?::(\d{2}))?\s*(am|pm)?\s*' . $dash . '\s*(\d{1,2})(?::(\d{2}))?\s*(am|pm)?\b/iu';
        $range = null;
        if (preg_match($re, $txt, $m)) {
            $h1=(int)$m[1]; $m1=isset($m[2])?(int)$m[2]:0; $amp1=isset($m[3])?strtolower($m[3]):null;
            $h2=(int)$m[4]; $m2=isset($m[5])?(int)$m[5]:0; $amp2=isset($m[6])?strtolower($m[6]):null;

            if ($amp1 && !$amp2) $amp2 = $amp1;
            if ($amp2 && !$amp1) $amp1 = $amp2;

            $to24 = function(int $h, int $mi, ?string $amp) {
                if ($amp==='pm' && $h<12) $h+=12;
                if ($amp==='am' && $h===12) $h=0;
                return [sprintf('%02d:%02d',$h,$mi), $h, $mi];
            };
            [$t1,$H1,$M1] = $to24($h1,$m1,$amp1);
            [$t2,$H2,$M2] = $to24($h2,$m2,$amp2);

            if ($H2 < $H1 || ($H2==$H1 && $M2 <= $M1)) {
                $H2 = min(22, $H1+1);
                $t2 = sprintf('%02d:%02d',$H2,$M2);
            }
            $range = [$t1,$t2];
        }

        if (!$quick && !$range) return null;

        $tzObj = new \DateTimeZone($tz);
        $now   = new \DateTimeImmutable('now', $tzObj);
        $base  = match ($dayToken) {
            'today' => $now,
            'tomorrow' => $now->modify('+1 day'),
            default => $this->nextWeekday($now, $dayToken)
        };

        [$start,$end] = $quick ? $quick : $range;

        if ($start < '08:00') $start = '08:00';
        if ($end   > '22:00') $end   = '22:00';

        return [
            'date'  => $base->format('Y-m-d'),
            'start' => $start,
            'end'   => $end,
        ];
    }



    private function formatUsSlot(string $date, string $time, string $tz): string
    {
        $tzObj = new \DateTimeZone($tz);
        $dt    = new \DateTimeImmutable($date.' '.$time, $tzObj);
        $now   = new \DateTimeImmutable('now', $tzObj);

        $sameYear = ($dt->format('Y') === $now->format('Y'));
        $mdy = $sameYear ? $dt->format('n/j') : $dt->format('n/j/Y');
        $ampm = $dt->format('g:i A'); // 9:00 AM

        return $mdy.' '.$ampm;
    }

    private function formatUsTime(string $time): string
    {
        [$H,$M] = array_map('intval', explode(':', $time));
        $ampm   = ($H >= 12) ? 'PM' : 'AM';
        $h12    = $H % 12; if ($h12 === 0) $h12 = 12;
        return sprintf('%d:%02d %s', $h12, $M, $ampm); // 9:00 AM
    }


    private function normalizeWithAI(string $clientText, string $tz, array $knownCities): array
    {
        $schema = <<<JSON
{
  "type": "object",
  "properties": {
    "time_exact": { "type": "string", "description": "HH:MM in 24h, local timezone" },
    "time_window": { "type": "object", "properties": {
        "start": { "type": "string" }, "end": { "type": "string" }, "date": { "type": "string" }
    }},
    "date": { "type": "string", "description": "YYYY-MM-DD" },
    "city": { "type": "string" }
  }
}
JSON;

        $known = implode(', ', $knownCities);
        $prompt = "You normalize booking intent. Timezone: {$tz}. Known cities: {$known}. ".
            "User said: <<<{$clientText}>>>. ".
            "Return ONLY compact JSON (no markdown, no prose) matching schema: {$schema}. ".
            "Rules: resolve relative dates (today/tomorrow/weekday) to YYYY-MM-DD; ".
            "If 'anytime' or vague part-of-day, convert to a reasonable window within 08:00-22:00; ".
            "Prefer 24-hour times.";

        try {
            // re-use dialogue id if you keep one; ok to call without it too
            $resp = $this->aIModel->ask($prompt);
            $text = $resp['text'] ?? '';
            $json = $this->parseJsonLoose($text);

            if (is_array($json)) {
                // sanity fixes
                if (!empty($json['time_exact']) && !preg_match('/^\d{2}:\d{2}$/', $json['time_exact'])) {
                    // normalize "3pm" → "15:00"
                    if (preg_match('/^(\d{1,2})\s*(am|pm)$/i', $json['time_exact'], $m)) {
                        $h=(int)$m[1]; $amp=strtolower($m[2]);
                        if ($amp==='pm' && $h<12) $h+=12;
                        if ($amp==='am' && $h===12) $h=0;
                        $json['time_exact'] = sprintf('%02d:00',$h);
                    }
                }
                return $json;
            }
        } catch (\Throwable $e) {
        }
        return [];
    }

    private function parseJsonLoose(string $s): ?array
    {
        if (preg_match('/\{.*\}/s', $s, $m)) {
            $j = json_decode($m[0], true);
            if (is_array($j)) return $j;
        }
        if (preg_match('/```json\s*(\{.*\})\s*```/s', $s, $m)) {
            $j = json_decode($m[1], true);
            if (is_array($j)) return $j;
        }
        return null;
    }

    private function nextWeekday(\DateTimeImmutable $from, string $weekday): \DateTimeImmutable
    {
        $map=['monday'=>1,'tuesday'=>2,'wednesday'=>3,'thursday'=>4,'friday'=>5,'saturday'=>6,'sunday'=>7];
        $t=$map[$weekday]??null; if(!$t) return $from;
        $w=(int)$from->format('N'); $d=$t-$w; if($d<=0)$d+=7;
        return $from->modify("+{$d} days");
    }

    private function toUnix(string $date, string $time, string $tz): int
    {
        $tzObj = new \DateTimeZone($tz);
        return (new \DateTimeImmutable("{$date} {$time}", $tzObj))->getTimestamp();
    }

    private function getBusySlotsSafe(): array
    {
        if (method_exists($this->scheduleModel, 'getSlots')) {
            $slots = $this->scheduleModel->getSlots();
            if (is_array($slots)) return $slots;
        }
        if (method_exists($this->scheduleModel, 'allSlots')) {
            $slots = $this->scheduleModel->allSlots();
            if (is_array($slots)) return $slots;
        }

        $ctx = $this->aIModel->getSystemContext();
        if (preg_match('/\[SCHEDULE_JSON\](.*?)\[\/SCHEDULE_JSON\]/s', $ctx, $m)) {
            $json = trim($m[1]);
            $decoded = json_decode($json, true);
            if (isset($decoded['slots']) && is_array($decoded['slots'])) {
                return $decoded['slots'];
            }
        }
        return [];
    }


    private function proposeFreeNearBusy(array $busy, string $city, ?string $date, ?string $prefTime, ?array $window, string $tz, int $limit=3): array
    {
        $BUSY = array_values(array_filter($busy, fn($s)=>isset($s['city']) && mb_strtolower($s['city'],'UTF-8')===mb_strtolower($city,'UTF-8')));

        $busyIndex = [];
        $busyByWorker = [];
        foreach ($BUSY as $s) {
            $d=$s['date']; $t=$s['time']; $w=$s['worker_id'] ?? 'w1';
            $busyIndex["{$d} {$t}"] = true;
            $busyByWorker[$w]["{$d} {$t}"] = true;
        }

        $dates = [];
        if ($date) {
            $dates[] = $date;
        } else {
            $dmap = [];
            foreach ($BUSY as $s) $dmap[$s['date']] = true;
            $dates = array_slice(array_keys($dmap), 0, 3);
        }

        $toUnix = fn($d,$t)=>$this->toUnix($d,$t,$tz);
        $withinHours = function(string $t): bool {
            return $t >= '08:00' && $t <= '22:00';
        };

        $prefUnix = null; $winStart=null; $winEnd=null;
        if ($date && $prefTime) $prefUnix = $toUnix($date, $prefTime);
        if ($window && isset($window['date'],$window['start'],$window['end'])) {
            $winStart = $toUnix($window['date'],$window['start']);
            $winEnd   = $toUnix($window['date'],$window['end']);
        }

        $cand = [];
        foreach ($BUSY as $s) {
            if ($dates && !in_array($s['date'], $dates, true)) continue;

            $w = $s['worker_id'] ?? 'w1';
            foreach ([-1, +1] as $deltaH) {
                $t = $this->shiftTime($s['time'], $deltaH);
                if (!$t) continue;
                if (!$withinHours($t)) continue;

                $key = "{$s['date']} {$t}";
                if (!empty($busyIndex[$key])) continue;

                $unix = $toUnix($s['date'],$t);
                if ($winStart && $winEnd && ($unix < $winStart || $unix > $winEnd)) continue;

                $score = 0;

                if ($prefUnix) {
                    $score += abs($unix - $prefUnix);
                } elseif ($winStart && $winEnd) {
                    if ($unix >= $winStart && $unix <= $winEnd) {
                        $score += (int)($unix - $winStart);
                    }
                }

                $score += 5;
                if ($deltaH === -1 || $deltaH === +1) $score -= 3;

                $score += $unix / 1000;

                $candKey = "{$s['date']}|{$t}|{$w}";
                if (!isset($cand[$candKey])) {
                    $cand[$candKey] = [
                        'date'      => $s['date'],
                        'time'      => $t,
                        'city'      => $city,
                        'worker_id' => $w,
                        'unix'      => $unix,
                        '_score'    => $score
                    ];
                }
            }
        }

        if (empty($cand) && $date && $prefTime) {
            foreach ([-1, +1, -2, +2] as $dh) {
                $t = $this->shiftTime($prefTime, $dh);
                if (!$t) continue;
                if (!$withinHours($t)) continue;
                $key = "{$date} {$t}";
                if (!empty($busyIndex[$key])) continue;

                $w = $this->pickBestWorker($busy, $city, $date, $t, $tz) ?? 'w1';
                $unix = $toUnix($date,$t);
                $cand["{$date}|{$t}|{$w}"] = [
                    'date'=>$date,'time'=>$t,'city'=>$city,'worker_id'=>$w,'unix'=>$unix,'_score'=>abs($unix-($prefUnix??$unix))
                ];
                if (count($cand) >= $limit) break;
            }
        }

        if (empty($cand)) return [];

        usort($cand, fn($a,$b)=> $a['_score'] <=> $b['_score']);
        $cand = array_values(array_slice($cand, 0, $limit));
        // чистим служебное поле
        foreach ($cand as &$c) unset($c['_score']);
        return $cand;
    }

    private function shiftTime(string $time, int $deltaHours): ?string
    {
        if (!preg_match('/^(\d{2}):(\d{2})$/', $time, $m)) return null;
        $h=(int)$m[1]; $mi=(int)$m[2];
        $h+= $deltaHours;
        if ($h<0 || $h>23) return null;
        return sprintf('%02d:%02d', $h, $mi);
    }


    private function pickBestWorker(array $busy, string $city, string $date, string $time, string $tz): ?string
    {
        $bestW=null; $bestDiff=null;
        $target = $this->toUnix($date,$time,$tz);

        foreach ($busy as $s) {
            if (mb_strtolower($s['city'],'UTF-8')!==mb_strtolower($city,'UTF-8')) continue;
            if ($s['date'] !== $date) continue;
            if (empty($s['worker_id'])) continue;
            $u = $this->toUnix($s['date'],$s['time'],$tz);
            $diff = abs($u - $target);
            if ($bestDiff===null || $diff < $bestDiff) {
                $bestDiff = $diff;
                $bestW = $s['worker_id'];
            }
        }
        return $bestW;
    }




    private function humanizeWhen(int $unix, string $tz): string
    {
        $tzObj = new \DateTimeZone($tz);
        $now   = new \DateTimeImmutable('now', $tzObj);
        $dt    = (new \DateTimeImmutable('@'.$unix))->setTimezone($tzObj);

        $today    = $now->format('Y-m-d');
        $tomorrow = $now->modify('+1 day')->format('Y-m-d');

        $dateYmd = $dt->format('Y-m-d');
        $t12     = $dt->format('g:i A');  // 9:00 AM

        if ($dateYmd === $today)    return "today {$t12}";
        if ($dateYmd === $tomorrow) return "tomorrow {$t12}";
        if ($dt <= $now->modify('+6 days')) return $dt->format('l').' '.$t12;

        $sameYear = ($dt->format('Y') === $now->format('Y'));
        $md       = $sameYear ? $dt->format('n/j') : $dt->format('n/j/Y');
        return $md.' '.$t12; // 9/21 9:00 AM
    }

    private function makeFinalConfirmation(array $sess, string $tz): string
    {
        $when = $this->humanizeWhen($sess['time_unix'], $tz);
        return "Thank you! See you then — scheduled {$when} at {$sess['address']}.";
    }
}