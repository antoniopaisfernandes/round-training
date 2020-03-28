@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <program-edition-list :items='@json($programEditions->items())'></program-edition-list>
            {{-- <program-edition-list :items='@json($programEditions->items())'></program-edition-list> --}}
        </v-col>
    </v-row>
</v-container>
@endsection
