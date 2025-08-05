<?php

namespace Engine;

class Debug
{
    private static $instance;
    private string $initTime;
    private bool $status;
    private bool $jsonView;
    private array $reports = [];

    public static function init(): Debug
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->initTime = microtime(true);
        }
        return self::$instance;
    }

    public function getInitTime(): string
    {
        return $this->initTime;
    }

    public function getMemory(): string
    {
        return memory_get_usage();
    }

    public function getExecutionTime(): string
    {
        return microtime(true) - $this->initTime;
    }

    public function setStatus($status = false): void
    {
        $this->status = $status;
    }

    public function setJsonView($jsonView = false): void
    {
        $this->jsonView = $jsonView;
    }

    public function jsonView(): bool
    {
        return $this->jsonView;
    }

    public function enabled(): bool
    {
        return $this->status;
    }

    public function addReport($data, $source, $type): void
    {
        if ($this->enabled()) {
            $report['data'] = $data;
            $report['type'] = $type;
            $report['time'] = microtime(true) - $this->initTime;
            $this->reports[$source][] = $report;
        }
    }

    public function getSources(): array|null
    {
        return array_keys($this->reports);
    }

    public function getReport($source): array|null
    {
        return $this->reports[$source];
    }

    public function getReports(): array|null
    {
        return $this->reports;
    }
}