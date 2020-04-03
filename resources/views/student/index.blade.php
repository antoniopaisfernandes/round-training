@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <student-index :items='@json($students->items())'></student-index>
        </v-col>
    </v-row>
</v-container>
@endsection
