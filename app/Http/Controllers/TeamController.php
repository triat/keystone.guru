<?php
/**
 * Created by PhpStorm.
 * User: wouterk
 * Date: 12-4-2019
 * Time: 00:02
 */

namespace App\Http\Controllers;

use App\Http\Requests\TeamFormRequest;
use App\Models\File;
use App\Models\Team;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Session;
use Teapot\StatusCode;

class TeamController extends Controller
{
    /**
     * @param TeamFormRequest $request
     * @param Team $team
     * @return mixed
     * @throws Exception
     */
    public function store($request, Team $team = null)
    {
        $new = $team === null;
        if ($new) {
            $team = new Team();
            $team->name = $request->get('name');
        }

        /** @var Team $team */
        $team->description = $request->get('description');
        $team->invite_code = Team::generateRandomInviteCode();
        $team->icon_file_id = -1;

        $logo = $request->file('logo');

        // Update or insert it
        if ($team->save()) {
            // Save was successful, now do any file handling that may be necessary
            if ($logo !== null) {
                try {
                    // Delete the icon should it exist already
                    if ($team->iconfile !== null) {
                        $team->iconfile->delete();
                    }

                    $icon = File::saveFileToDB($logo, $team, 'uploads');

                    // Update the expansion to reflect the new file ID
                    $team->icon_file_id = $icon->id;
                    $team->save();
                } catch (Exception $ex) {
                    if ($new) {
                        // Roll back the saving of the expansion since something went wrong with the file.
                        $team->delete();
                    }
                    throw $ex;
                }
            }

            if ($new) {
                // If saving team + logo was successful, save our own user as its first member
                $team->addMember(Auth::user(), 'admin');
            }
        }

        return $team;
    }

    /**
     * @return Factory|View
     */
    public function new()
    {
        return view('team.new');
    }

    /**
     * @param Request $request
     * @param Team $team
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(Request $request, Team $team)
    {
        $this->authorize('edit', $team);

        return view('team.edit', ['model' => $team]);
    }

    /**
     * @param Request $request
     * @param Team $team
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function delete(Request $request, Team $team)
    {
        $this->authorize('delete', $team);

        try {
            $team->delete();
        } catch (Exception $ex) {
            abort(500);
        }

        return redirect()->route('team.list');
    }

    /**
     * @param TeamFormRequest $request
     * @param Team $team
     * @return Factory|View
     * @throws Exception
     */
    public function update(TeamFormRequest $request, Team $team)
    {
        $this->authorize('edit', $team);

        // Store it and show the edit page again
        $team = $this->store($request, $team);

        // Message to the user
        Session::flash('status', __('Team updated'));

        // Display the edit page
        return $this->edit($request, $team);
    }

    /**
     * @param TeamFormRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function savenew(TeamFormRequest $request)
    {
        // Store it and show the edit page
        $team = $this->store($request);

        // Message to the user
        Session::flash('status', __('Team created'));

        return redirect()->route('team.edit', ['team' => $team]);
    }

    /**
     * Handles the viewing of a collection of items in a table.
     *
     * @return Factory|
     */
    public function list()
    {
        $user = Auth::user();
        return view('team.list', ['models' => $user->teams]);
    }

    /**
     * @param Request $request
     * @param string $invitecode
     * @return Factory|View
     */
    public function invite(Request $request, string $invitecode)
    {
        /** @var Team $team */
        $team = Team::where('invite_code', $invitecode)->first();
        $result = null;

        if ($team !== null) {
            if ($team->isCurrentUserMember()) {
                $result = view('team.invite', ['team' => $team, 'member' => true]);
            } else {
                $result = view('team.invite', ['team' => $team]);
            }
        } else {
            abort(StatusCode::NOT_FOUND, 'Unable to find a team associated with this invite code');
        }

        return $result;
    }

    /**
     * @param Request $request
     * @param string $invitecode
     * @return Factory|View
     */
    public function inviteaccept(Request $request, string $invitecode)
    {
        /** @var Team $team */
        $team = Team::where('invite_code', $invitecode)->first();

        if ($team->isCurrentUserMember()) {
            $result = view('team.invite', ['team' => $team, 'member' => true]);
        } else {
            $team->addMember(Auth::getUser(), 'member');

            Session::flash('status', sprintf(__('Success! You are now a member of team %s.'), $team->name));
            $result = redirect()->route('team.edit', ['team' => $team]);
        }

        return $result;
    }
}
