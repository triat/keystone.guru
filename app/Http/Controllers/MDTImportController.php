<?php

namespace App\Http\Controllers;

use App\Logic\MDT\IO\ImportString;
use App\Models\MDTImport;
use Illuminate\Http\Request;

class MDTImportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function import(Request $request)
    {
        $string = $request->get('import_string');

        $importString = new ImportString();

        // @TODO improve exception handling
        $dungeonRoute = $importString->setEncodedString($string)->getDungeonRoute();

        // Keep track of the import
        $mdtImport = new MDTImport();
        $mdtImport->dungeon_route_id = $dungeonRoute->id;
        $mdtImport->import_string = $string;
        $mdtImport->save();

        dd($dungeonRoute);


        return view('home');
    }
}
