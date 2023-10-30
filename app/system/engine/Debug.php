<?php

namespace Engine;

class Debug
{
    private static $instance;
    private bool $status;
    private array $reports = [];

    public static function init(): Debug
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setStatus($status=false): void
    {
        $this->status=$status;
    }
    public function enabled(): bool
    {
        return $this->status;
    }

    public function addReport($data, $source, $type): void
    {
        $report['data']=$data;
        $report['type']=$type;
        $report['time']=microtime(true);
        $this->reports[$source][] = $report;
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