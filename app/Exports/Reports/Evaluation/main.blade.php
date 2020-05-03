<!DOCTYPE html>
<html>
	<head>
        <title>Avaliação</title>
    </head>
    <style>
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
    </style>
    <body>
        <img src="{{ $logo }}" alt="logo.png" title="logo.png">
        <table>
            <tr>
                <td colspan="6" style="text-align: right; vertical-align: top">Mod DQUAL 034/5 (2018-11-30)</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: center">Avaliação da Eficácia da Formação</td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="3">Ação de Formação: {{ $programEdition->full_name }}</td>
                <td colspan="2">Duração: {{ $programEdition->schedules->sum('working_hours') }} horas</td>
                <td style="text-align: right">Data: {{ $programEdition->starts_at }}</td>
            </tr>
            <tr>
                <td colspan="6">Objetivos da ação de Formação: {{ $programEdition->goals }}</td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2" style="text-align: center">Necessário repetir a formação</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">Colaborador</td>
                <td style="text-align: center; width: 20;">AVALIAÇÃO GLOBAL</td>
                <td style="text-align: center; width: 15;">Observações</td>
                <td style="text-align: center; width: 15;">SIM</td>
                <td style="text-align: center; width: 15;">NÃO</td>
                <td style="text-align: center; width: 15;">No prazo de:</td>
            </tr>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row['student_name'] }}</td>
                    <td>{{ $row['global_evaluation'] }}</td>
                    <td>{{ $row['evaluation_comments'] }}</td>
                    <td style="text-align: center">{{ $row['program_should_be_repeated'] ? 'X' : '' }}</td>
                    <td style="text-align: center">{{ $row['program_should_not_be_repeated'] ? 'X' : '' }}</td>
                    <td style="text-align: center">{{ $row['should_be_repeated_in_months'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="3">Responsável pela avaliação de eficácia:</td>
                <td colspan="3">Data:</td>
            </tr>
            <tr>
                <td colspan="6">1) Assinalar E - Eficaz; NE - Não Eficaz</td>
            </tr>
        </table>
    </body>
</html>
