<?php

namespace Samples\M;

use Engine\Config;


class AIModel
{
    private Config $config;
    private string $systemContext = '';

    public function __construct(
        Config $config,
    )
    {
        $this->config = $config;
    }

    public function setContext(string $context): void
    {
        $this->systemContext = $this->sanitize($context);
    }
    public function getSystemContext(): string { return $this->systemContext; }




    public function ask(string $userMessage, ?string $prevResponseId = null): array
    {
        $prompt = $this->limitLength($this->sanitize($userMessage), 4000);

        if ($this->systemContext !== '') {
            $prompt = "[SYSTEM]\n{$this->systemContext}\n\n[USER]\n{$prompt}";
        }

        $payload = [
            'model' => $this->config->get('openAIModel'),
            'input' => $prompt,
            'store' => true,
        ];
        if ($prevResponseId) {
            $payload['previous_response_id'] = $prevResponseId;
        }

        $ch = curl_init($this->config->get('openAIEndPoint'));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: ' . 'Bearer ' . $this->config->get('openAIKey'),
            ],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT        => 30
        ]);

        $raw = curl_exec($ch);
        if ($raw === false) {
            throw new \RuntimeException('CURL Error: ' . curl_error($ch));
        }
        curl_close($ch);

        $decoded = json_decode($raw, true);

        if (isset($decoded['error'])) {
            $msg = $decoded['error']['message'] ?? 'Unknown error';
            $param = $decoded['error']['param'] ?? null;
            $code = $decoded['error']['code'] ?? null;
            throw new \RuntimeException("OpenAI error: {$msg}" . ($param ? " [param: {$param}]" : "") . ($code ? " [code: {$code}]" : ""));
        }


        $text = '';
        if (!empty($decoded['output_text']) && is_string($decoded['output_text'])) {
            $text = $decoded['output_text'];
        } elseif (!empty($decoded['output'][0]['content']) && is_array($decoded['output'][0]['content'])) {
            foreach ($decoded['output'][0]['content'] as $c) {
                if (isset($c['text']) && is_string($c['text'])) $text .= $c['text'];
            }
        }

        return [
            'text'           => $text,
            'responseId'     => $decoded['id'],
        ];
    }

    private function sanitize(string $s): string
    {
        $s = preg_replace('/[\x00-\x1F\x7F]/u', '', $s);
        $s = preg_replace('/[ \t]+/u', ' ', $s);
        $s = preg_replace("/\R/u", "\n", $s);
        $s = trim($s);
        $s = htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        if (class_exists('\Normalizer')) {
            $s = \Normalizer::normalize($s, \Normalizer::FORM_C);
        }
        return $s;
    }

    private function limitLength(string $s, int $max): string
    {
        if (mb_strlen($s, 'UTF-8') <= $max) return $s;
        return mb_substr($s, 0, $max, 'UTF-8') . ' …[truncated]';
    }
}