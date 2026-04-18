<?php

namespace machaon\Test;

class EventsHandler
{
    public static function registerVisit(): void
    {
        try {
            if(!defined('NO_KEEP_STATISTIC')) {
                Visitor::current()->registerVisit();
            }
        } catch (\Exception $e) {}
    }
}