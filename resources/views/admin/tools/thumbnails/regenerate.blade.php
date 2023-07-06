@extends('layouts.sitepage', ['showAds' => false, 'title' => __('views/admin.tools.thumbnails.regenerate.title')])

@section('header-title', __('views/admin.tools.thumbnails.regenerate.header'))

@section('content')
    {{ Form::open(['route' => 'admin.tools.thumbnails.regenerate.submit']) }}
    <div class="form-group">
        @include('common.dungeon.select', ['activeOnly' => false])
    </div>
    <div class="form-group">
        {!! Form::submit(__('views/admin.tools.thumbnails.regenerate.submit'), ['class' => 'btn btn-primary col-md-auto']) !!}
        <div class="col-md">

        </div>
    </div>
    {{ Form::close() }}
@endsection
