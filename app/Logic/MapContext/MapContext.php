<?php


namespace App\Logic\MapContext;

use App\Http\Controllers\Traits\ListsEnemies;
use App\Models\CharacterClass;
use App\Models\Faction;
use App\Models\Floor;
use App\Models\MapIconType;
use App\Models\PublishedState;
use App\Models\RaidMarker;
use App\Models\Spell;
use App\Service\Cache\CacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

abstract class MapContext
{
    use ListsEnemies;

    /** @var Model */
    protected Model $_context;

    /**
     * @var Floor
     */
    private Floor $_floor;

    function __construct(Model $context, Floor $floor)
    {
        $this->_context = $context;
        $this->_floor = $floor;
    }

    /**
     * @return string
     */
    public abstract function getType(): string;

    /**
     * @return bool
     */
    public abstract function isTeeming(): bool;

    /**
     * @return int
     */
    public abstract function getSeasonalIndex(): int;

    /**
     * @return array
     */
    public abstract function getEnemies(): array;

    /**
     * @return array
     */
    public function toArray(): array
    {
        /** @var CacheService $cacheService */
        $cacheService = App::make(CacheService::class);

        // Get the DungeonData
        $dungeonData = $cacheService->getOtherwiseSet(sprintf('dungeon_%s', $this->_floor->dungeon->id), function ()
        {
            $dungeon = $this->_floor->dungeon->load(['enemies', 'enemypacks', 'enemypatrols', 'mapicons']);

            // Bit of a loss why the [0] is needed - was introduced after including the without() function
            return array_merge(($this->_floor->dungeon()->without(['mapicons', 'enemypacks'])->get()->toArray())[0], $this->getEnemies(), [
                'enemies'                   => $dungeon->enemies()->without(['npc'])->get()->makeHidden(['enemyactiveauras']),
                'npcs'                      => $dungeon->npcs()->with(['spells'])->get(),
                'auras'                     => Spell::where('aura', true)->get(),
                'enemyPacks'                => $dungeon->enemypacks()->with(['enemies:enemies.id,enemies.enemy_pack_id'])->get(),
                'enemyPatrols'              => $dungeon->enemypatrols,
                'mapIcons'                  => $dungeon->mapicons,
                'dungeonFloorSwitchMarkers' => $dungeon->floorswitchmarkers
            ]);
        });

        $static = $cacheService->getOtherwiseSet('static_data', function ()
        {
            return [
                'mapIconTypes'                      => MapIconType::all(),
                'unknownMapIconType'                => MapIconType::find(1),
                'awakenedObeliskGatewayMapIconType' => MapIconType::find(11),
                'classColors'                       => CharacterClass::all()->pluck('color'),
                'raidMarkers'                       => RaidMarker::all(),
                'factions'                          => Faction::where('name', '<>', 'Unspecified')->with('iconfile')->get(),
                'publishStates'                     => PublishedState::all(),
            ];
        });

        $npcMinHealth = $this->_floor->dungeon->getNpcsMinHealth();
        $npcMaxHealth = $this->_floor->dungeon->getNpcsMaxHealth();

        // Prevent the values being exactly the same, which causes issues in the front end
        $npcMaxHealth = $npcMaxHealth + ($npcMinHealth === $npcMaxHealth ? 1 : 0);

        return [
            'type'                => $this->getType(),
            'floorId'             => $this->_floor->id,
            'teeming'             => $this->isTeeming(),
            'seasonalIndex'       => $this->getSeasonalIndex(),
            'dungeon'             => $dungeonData,
            'static'              => $static,
            'auras'               => Spell::where('aura', true)->get(),
            'minEnemySizeDefault' => config('keystoneguru.min_enemy_size_default'),
            'maxEnemySizeDefault' => config('keystoneguru.max_enemy_size_default'),
            // @TODO Probably move this? Temp fix
            'npcsMinHealth'       => $npcMinHealth,
            'npcsMaxHealth'       => $npcMaxHealth,
        ];
    }
}