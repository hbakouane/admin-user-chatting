@extends('layouts.app')

@section('content')
<div class="container">
    @livewire('message', ['users' => $users])
</div>
@endsection
