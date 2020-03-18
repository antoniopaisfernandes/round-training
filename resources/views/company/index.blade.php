@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <company-list :items='@json($companies->items())'></company-list>
        </v-col>
    </v-row>
</v-container>
@endsection
