@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            <company-index :items='@json($companies)'></company-index>
        </v-col>
    </v-row>
</v-container>
@endsection
