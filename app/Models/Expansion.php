<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $icon_file_id
 * @property string $name
 * @property string $shortname
 * @property string $color
 *
 * @property Carbon $released_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection|Dungeon[] $dungeons
 *
 * @mixin Eloquent
 */
class Expansion extends IconFileModel
{
    public $fillable = ['icon_file_id', 'name', 'shortname', 'color', 'released_at'];

    public $hidden = ['id', 'icon_file_id', 'created_at', 'updated_at'];

    protected $dates = [
//        'released_at',
        'created_at',
        'updated_at'
    ];

    const EXPANSION_LEGION = 'legion';
    const EXPANSION_BFA = 'bfa';
    const EXPANSION_SHADOWLANDS = 'shadowlands';

    const ALL = [
        'Legion' => self::EXPANSION_LEGION,
        'Battle for Azeroth' => self::EXPANSION_BFA,
        'Shadowlands' => self::EXPANSION_SHADOWLANDS,
    ];

    /**
     * @return HasMany
     */
    public function dungeons()
    {
        return $this->hasMany('App\Models\Dungeon');
    }

    /**
     * Saves an expansion with the data from a Request.
     *
     * @param Request $request
     * @param string $fileUploadDirectory
     * @throws Exception
     */
    public function saveFromRequest(Request $request, $fileUploadDirectory = 'uploads')
    {
        $new = isset($this->id);

        $file = $request->file('icon');

        $this->icon_file_id = -1;
        $this->name = $request->get('name');
        $this->shortname = $request->get('shortname');
        $this->color = $request->get('color');

        // Update or insert it
        if ($this->save()) {
            // Save was successful, now do any file handling that may be necessary
            if ($file !== null) {
                try {
                    $icon = File::saveFileToDB($file, $this, $fileUploadDirectory);

                    // Update the expansion to reflect the new file ID
                    $this->icon_file_id = $icon->id;
                    $this->save();
                } catch (Exception $ex) {
                    if ($new) {
                        // Roll back the saving of the expansion since something went wrong with the file.
                        $this->delete();
                    }
                    throw $ex;
                }
            }
        }
    }
}
