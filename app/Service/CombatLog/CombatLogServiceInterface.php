<?php

namespace App\Service\CombatLog;

use App\Logic\CombatLog\BaseEvent;
use App\Service\CombatLog\Models\ChallengeMode;
use Illuminate\Support\Collection;

interface CombatLogServiceInterface
{
    /**
     * @param string $filePath
     *
     * @return Collection|BaseEvent[]
     */
    public function parseCombatLogToEvents(string $filePath): Collection;

    /**
     * @param string $filePath
     *
     * @return Collection|ChallengeMode[]
     */
    public function getChallengeModes(string $filePath): Collection;

    /**
     * @param string $filePath
     * @return string|null
     */
    public function extractCombatLog(string $filePath): ?string;

    /**
     * @param string $filePathToTxt
     * @return string
     */
    public function compressCombatLog(string $filePathToTxt): string;

    /**
     * @param string $filePath
     * @param callable $callback
     * @return void
     */
    public function parseCombatLog(string $filePath, callable $callback): void;

    /**
     * @param Collection $rawEvents
     * @param string $filePath
     * @return bool
     */
    public function saveCombatLogToFile(Collection $rawEvents, string $filePath): bool;


}
