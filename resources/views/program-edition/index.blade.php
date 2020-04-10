@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <program-edition-index
                :items='@json($programEditions->items())'
                :total-items='{{ $programEditions->resource->total() }}'
            ></program-edition-index>
        </v-col>
    </v-row>
</v-container>
@endsection
