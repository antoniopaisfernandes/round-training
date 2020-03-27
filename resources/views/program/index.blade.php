@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <program-list :items='@json($programs)'></program-list>
        </v-col>
    </v-row>
</v-container>
@endsection
