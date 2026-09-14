<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening\Service;

use KejawenLab\Application\SemartHris\Component\Screening\PhoneMasker;
use KejawenLab\Application\SemartHris\Component\Screening\ScreeningStatus;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;

/**
 * Call engine abstraction for AI phone screening.
 * Ported from HireCall CallEService adapter.
 *
 * mock: sandbox simulator, transitions candidates to completed locally.
 * live: real outbound dialing must be wired to the CALL-E worker;
 *       this service only marks candidates as calling and returns
 *       the payload the worker needs. It never places a call by itself.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CallScreenEngine
{
    const MODE_MOCK = 'mock';
    const MODE_LIVE = 'live';

    /**
     * @var Setting
     */
    private $setting;

    /**
     * @var string
     */
    private $modeKey;

    /**
     * @param Setting $setting
     * @param string  $modeKey
     */
    public function __construct(Setting $setting, string $modeKey = 'SEMART_CALLSCREEN_MODE')
    {
        $this->setting = $setting;
        $this->modeKey = $modeKey;
    }

    /**
     * @return bool true when real outbound calling is enabled
     */
    public function isLive(): bool
    {
        return self::MODE_LIVE === strtolower((string) $this->setting->get($this->modeKey));
    }

    /**
     * @return string mock|live
     */
    public function getMode(): string
    {
        return $this->isLive() ? self::MODE_LIVE : self::MODE_MOCK;
    }

    /**
     * Builds the pre-flight confirmation payload. Full phone numbers
     * are included here and only here, the list views stay masked.
     *
     * @param array  $candidates each row: id, name, phone
     * @param string $jobTitle
     *
     * @return array names, phones, maskedPhones, count, mode, jobTitle
     */
    public function buildConfirmation(array $candidates, string $jobTitle): array
    {
        $names = [];
        $phones = [];
        $masked = [];
        foreach ($candidates as $candidate) {
            $names[] = $candidate['name'];
            $phones[] = $candidate['phone'];
            $masked[] = PhoneMasker::mask($candidate['phone']);
        }

        return [
            'names' => $names,
            'phones' => $phones,
            'maskedPhones' => $masked,
            'count' => count($candidates),
            'mode' => $this->getMode(),
            'jobTitle' => $jobTitle,
        ];
    }

    /**
     * Status applied when a batch is launched.
     * Live mode parks candidates in calling until the CALL-E worker
     * reports back. Mock mode completes them immediately.
     *
     * @return string
     */
    public function launchStatus(): string
    {
        if ($this->isLive()) {
            return ScreeningStatus::CALLING;
        }

        return ScreeningStatus::COMPLETED;
    }
}
