@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row align="center" justify="center">
        <v-col>
            {{ __('app.reports') }}
        </v-col>
    </v-row>
    <v-row>
        <v-col>
            <report-card
                title="Budget execution"
                description="Execution and budget with program editions and companies details"
                report="execution"
                date-format="year"
            ></report-card>
        </v-col>
    </v-row>
</v-container>
@endsection
