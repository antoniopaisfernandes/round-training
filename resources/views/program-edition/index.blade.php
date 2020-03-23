@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <program-editions-list :items='@json($programEditions->items())'></program-editions-list>
        </v-col>
    </v-row>
</v-container>
@endsection
