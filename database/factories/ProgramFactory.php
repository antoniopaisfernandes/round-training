<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Program;
use Faker\Generator as Faker;

$courseNames = [
    'Anatomia Patológica',
    'Anestesiologia',
    'Angiologia e Cirurgia Vascular',
    'Cardiologia',
    'Cardiologia Pediátrica',
    'Cirurgia Cardíaca',
    'Cirurgia Cardiotorácica',
    'Cirurgia Geral',
    'Cirurgia Maxilofacial',
    'Cirurgia Pediátrica',
    'Cirurgia Plástica Reconstrutiva e Estética',
    'Cirurgia Torácica',
    'Dermatovenereologia',
    'Doenças Infecciosas',
    'Endocrinologia e Nutrição',
    'Estomatologia',
    'Gastrenterologia',
    'Genética Médica',
    'Ginecologia/Obstetrícia',
    'Imunoalergologia',
    'Imuno-hemoterapia',
    'Farmacologia Clínica',
    'Hematologia Clínica',
    'Medicina Desportiva',
    'Medicina do Trabalho',
    'Medicina Física e Reabilitação',
    'Medicina Geral e Familiar',
    'Medicina Intensiva',
    'Medicina Interna',
    'Medicina Legal',
    'Medicina Nuclear',
    'Medicina Tropical',
    'Nefrologia',
    'Neurocirurgia',
    'Neurologia',
    'Neurorradiologia',
    'Oftalmologia',
    'Oncologia Médica',
    'Ortopedia',
    'Otorrinolaringologia',
    'Patologia Clínica',
    'Pediatria',
    'Pneumologia',
    'Psiquiatria',
    'Psiquiatria da Infância e da Adolescência',
    'Radiologia',
    'Radioncologia',
    'Reumatologia',
    'Saúde Pública',
    'Urologia',
];

$factory->define(Program::class, function (Faker $faker) use ($courseNames) {
    return [
        'name' => $faker->randomElement($courseNames) . ' ' . mt_rand(1, 9999),
    ];
});
