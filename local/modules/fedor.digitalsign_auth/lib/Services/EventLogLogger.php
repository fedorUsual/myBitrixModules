<?php

namespace Fedor\DigitalsignAuth\Services;

use Bitrix\Main\Diag\EventLogger;
use Fedor\DigitalsignAuth\Module;
use Psr\Log\LogLevel;

class EventLogLogger extends EventLogger
{
    public function __construct(string $auditType = null, callable $callback = null)
    {
        parent::__construct(Module::MODULE_ID, 'FEDOR_CRYPTOPRO' . $auditType, $callback);
    }

    public function setAuditType(string $auditType)
    {
        $this->auditType = 'FEDOR_CRYPTOPRO' . $auditType;
    }

    protected function logMessage(string $level, string $message)
    {
        $logLevelConverter = [
            LogLevel::EMERGENCY => \CEventLog::SEVERITY_EMERGENCY,
            LogLevel::ALERT => \CEventLog::SEVERITY_ALERT,
            LogLevel::CRITICAL => \CEventLog::SEVERITY_CRITICAL,
            LogLevel::ERROR => \CEventLog::SEVERITY_ERROR,
            LogLevel::WARNING => \CEventLog::SEVERITY_WARNING,
            LogLevel::NOTICE => \CEventLog::SEVERITY_NOTICE,
            LogLevel::INFO => \CEventLog::SEVERITY_INFO,
            LogLevel::DEBUG => \CEventLog::SEVERITY_DEBUG,
        ];

        if (!isset(Module::LOG_LEVELS[$logLevelConverter[$level]])) {
            return;
        }

        if (Module::getOption('FEDOR_CRYPTOPRO_LOG_LEVEL') < Module::LOG_LEVELS[$logLevelConverter[$level]]) {
            return;
        }

        parent::logMessage($level, $message);
    }
}