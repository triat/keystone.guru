<?php
/** @var \Illuminate\Support\Collection $spellsSelect */
?>

<div id="pull_sidebar_workbench" class="pull_workbench p-2" style="display: none;">
    <div class="row no-gutters pull_workbench_row pull_workbench_header">
        <div class="col">
            <h5 id="pull_sidebar_workbench_header" class="text-center mt-1">

            </h5>
        </div>
    </div>

    <div class="row no-gutters pull_workbench_row">
        <div class="col">
            <div data-toggle="tooltip"
                 title="{{ __('views/common.maps.controls.pullsworkbench.description') }}">
                <button id="map_killzonessidebar_killzone_description" class="btn btn-primary"
                        data-toggle="modal" data-target="#map_killzonessidebar_killzone_description_modal">
                    <i class="fas fa-font"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row no-gutters pull_workbench_row">
        <div class="col">
            <div data-toggle="tooltip"
                 title="{{ __('views/common.maps.controls.pullsworkbench.spells') }}">
                <button id="map_killzonessidebar_killzone_spells" class="btn btn-primary"
                        data-toggle="modal" data-target="#map_killzonessidebar_killzone_spells_modal">
                    <i class="fas fa-magic"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row no-gutters pull_workbench_row">
        <div class="col">
            <div id="map_killzonessidebar_killzone_kill_area_label"
                 data-toggle="tooltip" title="">
                <button id="map_killzonessidebar_killzone_has_killzone"
                        class="btn btn-primary" data-toggle="button" aria-pressed="false">
                    <i class="fas fa-bullseye"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row no-gutters pull_workbench_row">
        <div class="col">
            <button id="map_killzonessidebar_killzone_color"
                    class="btn map_killzonessidebar_color_btn w-100">

            </button>
        </div>
    </div>

    <div class="row no-gutters pull_workbench_row">
        <div class="col">
            <div data-toggle="tooltip"
                 title="{{ __('views/common.maps.controls.pullsworkbench.delete_killzone') }}">
                <button id="map_killzonessidebar_killzone_delete" class="btn btn-danger">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>


@section('content')
    @parent

    @component('common.general.modal', ['id' => 'map_killzonessidebar_killzone_description_modal'])
        <div class="form-group">
            {!! Form::label(
                'map_killzonessidebar_killzone_description_modal_textarea',
                __('views/common.maps.controls.pullsworkbench.modal.description.label'),
                ['id' => 'map_killzonessidebar_killzone_description_modal_label']
            ) !!}
            {{ Form::textarea('map_killzonessidebar_killzone_description_modal_textarea', '', [
                'class' => 'form-control',
                'id' => 'map_killzonessidebar_killzone_description_modal_textarea',
            ]) }}
        </div>
        <div class="form-group">
            <div id="map_killzonessidebar_killzone_description_modal_save" class="btn btn-primary" data-dismiss="modal">
                {{ __('views/common.maps.controls.pullsworkbench.modal.description.save') }}
            </div>
        </div>
    @endcomponent

    @component('common.general.modal', ['id' => 'map_killzonessidebar_killzone_spells_modal'])
        <div class="form-group">
            {!! Form::label(
                'map_killzonessidebar_killzone_spells_modal_select',
                __('views/common.maps.controls.pullsworkbench.modal.spells.label'),
                ['id' => 'map_killzonessidebar_killzone_spells_modal_label']
            ) !!}
            <select id="map_killzonessidebar_killzone_spells_modal_select"
                    class="form-control selectpicker"
                    data-live-search="true"
                    multiple>
                @foreach($spellsSelect as $group => $spells)
                    <optgroup label="{{$group}}">
                        @foreach($spells as $spell)
                                <?php ob_start() ?>

                            @include('common.forms.select.imageoption', [
                                'url' => $spell['icon_url'],
                                'name' => $spell['name'],
                            ])

                                <?php $html = ob_get_clean(); ?>
                            <option value="{{ $spell['id'] }}" data-content="{{{$html}}}">{{ $spell['name'] }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <div id="map_killzonessidebar_killzone_spells_modal_save" class="btn btn-primary" data-dismiss="modal">
                {{ __('views/common.maps.controls.pullsworkbench.modal.spells.save') }}
            </div>
        </div>
    @endcomponent
@endsection
