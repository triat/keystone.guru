<?php

namespace App\Console\Commands\Scheduler\Telemetry;

use App\Console\Commands\Scheduler\Telemetry\Measurements\DungeonRouteCount;
use App\Console\Commands\Scheduler\Telemetry\Measurements\MachineStats;
use App\Console\Commands\Scheduler\Telemetry\Measurements\Measurement;
use App\Console\Commands\Scheduler\Telemetry\Measurements\TeamCount;
use App\Console\Commands\Scheduler\Telemetry\Measurements\UserCount;
use Exception;
use Illuminate\Console\Command;
use InfluxDB\Database;
use TrayLabs\InfluxDB\Facades\InfluxDB;

class Telemetry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:telemetry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes the telemetry to Grafana';

    /** @var array|Measurement[] */
    private $measurements;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->measurements = [
            // Site stats
            new UserCount(),
            new TeamCount(),
            new DungeonRouteCount(),

            // Machine
            new MachineStats()
        ];
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $points = [];

        foreach ($this->measurements as $measurement) {
            $points = array_merge($points, $measurement->getPoints());
        }

        // This function does actually exist
        InfluxDB::writePoints($points, Database::PRECISION_SECONDS);

        return 0;
    }
}
