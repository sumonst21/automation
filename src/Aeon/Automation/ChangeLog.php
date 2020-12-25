<?php

declare(strict_types=1);

namespace Aeon\Automation;

use Aeon\Calendar\Gregorian\Day;

final class ChangeLog
{
    private string $release;

    private Day $day;

    /**
     * @var Changes[]
     */
    private array $changes;

    public function __construct(string $release, Day $day)
    {
        $this->release = $release;
        $this->day = $day;
        $this->changes = [];
    }

    public function release() : string
    {
        return $this->release;
    }

    public function day() : Day
    {
        return $this->day;
    }

    /**
     * @return Changes[]
     */
    public function changes() : array
    {
        return $this->changes;
    }

    public function add(Changes $changes) : void
    {
        $this->changes[] = $changes;
    }
}
