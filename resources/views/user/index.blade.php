@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <user-index
                :items='@json($users->items())'
                :total-items='{{ $users->resource->total() }}'
            ></user-index>
        </v-col>
    </v-row>
</v-container>
@endsection
