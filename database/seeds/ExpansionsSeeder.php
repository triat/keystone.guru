<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\File;
use Carbon\Carbon;

class ExpansionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->_rollback();

        $this->command->info('Adding known Expansions');

        $expansions = [
            'Legion' => new App\Models\Expansion([
                'shortname' => 'legion',
                'color' => '#27ff0f',
                'released_at' => Carbon::make('2016-08-30 00:00:00')
            ]), 'Battle for Azeroth' => new App\Models\Expansion([
                'shortname' => 'bfa',
                'color' => '#906554',
                'released_at' => Carbon::make('2018-08-14 00:00:00')
            ]), 'Shadowlands' => new App\Models\Expansion([
                'shortname' => 'shadowlands',
                'color' => '#5832a8',
                'released_at' => Carbon::make('2020-12-31 00:00:00')
            ])
        ];


        foreach ($expansions as $name => $expansion) {
            $expansion->name = $name;
            // Temp file
            $expansion->icon_file_id = -1;
            $expansion->save();

            $icon = new File();
            $icon->model_id = $expansion->id;
            $icon->model_class = get_class($expansion);
            $icon->disk = 'public';
            $icon->path = sprintf('images/expansions/%s.png', $expansion->shortname);
            $icon->save();

            $expansion->icon_file_id = $icon->id;
            $expansion->save();
        }
    }

    private function _rollback()
    {
        DB::table('expansions')->truncate();
        DB::table('files')->where('model_class', 'App\Models\Expansion')->delete();
    }
}
