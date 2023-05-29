<?php

namespace App\Logic\CombatLog\SpecialEvents;

use App\Logic\CombatLog\CombatEvents\GenericData;

/**
 * UNIT_DIED,0000000000000000,nil,0x80000000,0x80000000,Creature-0-4242-1841-14566-131402-0005E285EB,"Underrot Tick",0xa48,0x0,0
 *
 * @package App\Logic\CombatLog\SpecialEvents
 * @author Wouter
 * @since 26/05/2023
 */
class UnitDied extends SpecialEvent
{
    private GenericData $genericData;

    private bool $unconsciousOnDeath;

    /**
     * @return GenericData
     */
    public function getGenericData(): GenericData
    {
        return $this->genericData;
    }

    /**
     * @return bool
     */
    public function isUnconsciousOnDeath(): bool
    {
        return $this->unconsciousOnDeath;
    }

    /**
     * @param array $parameters
     * @return self
     */
    public function setParameters(array $parameters): self
    {
        parent::setParameters($parameters);

        $this->genericData = new GenericData();
        $this->genericData->setParameters(array_slice($parameters, 0, $this->genericData->getParameterCount()));

        $this->unconsciousOnDeath = (bool)$parameters[8];

        return $this;
    }

    /**
     * @return int
     */
    public function getParameterCount(): int
    {
        return 9;
    }


}
