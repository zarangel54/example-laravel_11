@extends('lists::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('lists.name') !!}</p>
@endsection
