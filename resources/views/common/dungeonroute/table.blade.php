@inject('seasonService', 'App\Service\Season\SeasonService')
<?php
/** @var $seasonService \App\Service\Season\SeasonService */
/** This is the template for the Affix Selection when using it in a dropdown */

/** @var \App\Models\DungeonRoute $model */
if (!isset($affixgroups)) {
    $affixgroups = $seasonService->getCurrentSeason()->affixgroups()->with('affixes')->get();
}
?>

<?php
$team = isset($team) ? $team : null;
/** @var string $view */
$cookieViewMode = isset($_COOKIE['routes_viewmode']) &&
($_COOKIE['routes_viewmode'] === 'biglist' || $_COOKIE['routes_viewmode'] === 'list') ?
    $_COOKIE['routes_viewmode'] : 'biglist';
?>
@include('common.general.inline', ['path' => 'dungeonroute/table',
        'options' =>  [
            'tableView' => $view,
            'viewMode' => $cookieViewMode,
            'teamName' => $team ? $team->name : '',
            'teams' => Auth::check() ? \App\User::findOrFail(Auth::id())->teams()->whereHas('teamusers', function($teamuser){
                /** @var $teamuser \App\Models\TeamUser  */
                $teamuser->isModerator(Auth::id());
            })->get() : []
            ]
])

@section('scripts')
    @parent

    <script type="text/javascript">
        $(function () {
            let code = _inlineManager.getInlineCode('dungeonroute/table');

            // Build the table
            code.refreshTable();
        });

    </script>
    @include('common.handlebars.groupsetup')
    @include('common.handlebars.affixgroups')
    @include('common.handlebars.routeattributes')
    @include('common.handlebars.affixgroupsselect')
    @include('common.handlebars.biglistfeatures')
    @include('common.handlebars.thumbnailcarousel')
@endsection

<div class="row no-gutters">
    @if($team instanceof \App\Models\Team)
        <div class="col-lg pl-1 pr-1">
            {!! Form::label('team_name', __('Team')) !!}
            {!! Form::text('team_name', $team->name, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
        </div>
    @endisset
    <div class="col-lg pl-1 pr-1">
        @include('common.dungeon.select', ['id' => 'dungeonroute_search_dungeon_id', 'showAll' => true, 'required' => false])
    </div>
    <div class="col-lg pl-1 pr-1">
        {!! Form::label('affixes[]', __('Affixes')) !!}
        {!! Form::select('affixes[]', $affixgroups->pluck('text', 'id'), null,
            ['id' => 'affixes',
            'class' => 'form-control affixselect selectpicker',
            'multiple' => 'multiple',
            'data-selected-text-format' => 'count > 1',
            'data-count-selected-text' => __('{0} affixes selected')]) !!}
    </div>
    <div class="col-lg pl-1 pr-1">
        @include('common.dungeonroute.attributes', [
        'selectedIds' => array_merge( [-1], \App\Models\RouteAttribute::all()->pluck('id')->toArray() ),
        'showNoAttributes' => true])
    </div>
    <div class="col-lg pl-1 pr-1">
        {!! Form::label('dungeonroute_requirements_select', __('Requirements')) !!}
        <?php
        $requirements = ['enough_enemy_forces' => __('Enough enemy forces')];
        if(Auth::check()){
            $requirements['favorite'] = __('Favorite');
        }
        ?>
        {!! Form::select('dungeon_id', $requirements, 0,
            ['id' => 'dungeonroute_requirements_select', 'class' => 'form-control selectpicker', 'multiple' => 'multiple',
            'data-selected-text-format' => 'count > 1', 'data-count-selected-text' => __('{0} requirements')]) !!}
    </div>
    <div class="col-lg pl-1 pr-1">
        <div class="mb-2">
            &nbsp;
        </div>
        <button id="dungeonroute_filter" class="btn btn-info col-lg">
            <i class="fas fa-filter"></i> {{ __('Filter') }}
        </button>
    </div>
    <div class="col-lg pl-1 pr-1">
        <div class="mb-2">
            &nbsp;
        </div>
        <div class="mb-2 text-right">
            <button id="table_biglist_btn"
                    class="btn {{ $cookieViewMode === 'biglist' ? 'btn-primary' : 'btn-default' }} table_list_view_toggle"
                    data-viewmode="biglist">
                <i class="fas fa-th-list"></i>
            </button>
            <button id="table_list_btn"
                    class="btn {{ $cookieViewMode === 'list' ? 'btn-primary' : 'btn-default' }}  table_list_view_toggle"
                    data-viewmode="list">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>
</div>
<table id="routes_table" class="routes_table tablesorter default_table dt-responsive nowrap table-striped mt-2"
       width="100%">
    <thead>
    </thead>

    <tbody>
    </tbody>
</table>