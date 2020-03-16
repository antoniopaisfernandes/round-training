@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach ($programs as $program)
            {{ $program->name }} <br />
        @endforeach
    </div>

    {{ $programs->links() }}
@endsection
