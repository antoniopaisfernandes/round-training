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
                title="Acompanhamento de execução"
                description="Execução face ao orçamento das ações de formação com detalhe por formação e empresa"
                report="execution"
                date-format="year"
            ></report-card>
        </v-col>
    </v-row>
</v-container>
@endsection
