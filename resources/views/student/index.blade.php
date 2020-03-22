@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <student-list :items='@json($students->items())'></student-list>
        </v-col>
    </v-row>
</v-container>
@endsection
