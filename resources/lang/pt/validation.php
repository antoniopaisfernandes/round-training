<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => 'O campo deverá ser aceite.',
    'active_url'           => 'O campo não contém um URL válido.',
    'after'                => 'O campo deverá conter uma data posterior a :date.',
    'after_or_equal'       => 'O campo deverá conter uma data posterior ou igual a :date.',
    'alpha'                => 'O campo deverá conter apenas letras.',
    'alpha_dash'           => 'O campo deverá conter apenas letras, números e traços.',
    'alpha_num'            => 'O campo deverá conter apenas letras e números .',
    'array'                => 'O campo deverá conter uma coleção de elementos.',
    'before'               => 'O campo deverá conter uma data anterior a :date.',
    'before_or_equal'      => 'O campo deverá conter uma data anterior ou igual a :date.',
    'between'              => [
        'numeric' => 'O campo deverá ter um valor entre :min - :max.',
        'file'    => 'O campo deverá ter um tamanho entre :min - :max kilobytes.',
        'string'  => 'O campo deverá conter entre :min - :max caracteres.',
        'array'   => 'O campo deverá conter entre :min - :max elementos.',
    ],
    'boolean'              => 'O campo deverá conter o valor verdadeiro ou falso.',
    'confirmed'            => 'A confirmação para o campo não coincide.',
    'date'                 => 'O campo não contém uma data válida.',
    'date_format'          => 'A data indicada para o campo não respeita o formato :format.',
    'different'            => 'Os campos e :other deverão conter valores diferentes.',
    'digits'               => 'O campo deverá conter :digits caracteres.',
    'digits_between'       => 'O campo deverá conter entre :min a :max caracteres.',
    'dimensions'           => 'A imagem :attribute tem dimensões inválidas.',
    'distinct'             => 'O campo :attribute tem um valor repetido.',
    'email'                => 'O campo não contém um endereço de correio eletrónico válido.',
    'ends_with'            => 'O campos :attribute tem de terminar com: :values',
    'exists'               => 'O valor selecionado para o campo é inválido.',
    'file'                 => 'O campo :attribute tem que ser um ficheiro.',
    'filled'               => 'É obrigatória a indicação de um valor para o campo.',
    'gt'                   => [
        'numeric' => 'O campo :attribute tem que ser maior que :value.',
        'file'    => 'O campo :attribute tem que ser maior que :value kilobytes.',
        'string'  => 'O campo :attribute tem que ser maior que :value caracteres.',
        'array'   => 'O campo :attribute tem que ter mais que :value itens.',
    ],
    'gte'                  => [
        'numeric' => 'O campo :attribute tem que ser maior ou igual a :value.',
        'file'    => 'O campo :attribute tem que ser maior ou igual a :value kilobytes.',
        'string'  => 'O campo :attribute tem que ser maior ou igual a :value caracteres.',
        'array'   => 'O campo :attribute tem que ter mais ou igual a :value itens.',
    ],
    'image'                => 'O campo deverá conter uma imagem.',
    'in'                   => 'O campo não contém um valor válido.',
    'in_array'             => 'O campo :attribute não existe em :other.',
    'integer'              => 'O campo deverá conter um número inteiro.',
    'ip'                   => 'O campo deverá conter um IP válido.',
    'ipv4'                 => 'O campo deverá conter um IPv4 válido.',
    'ipv6'                 => 'O campo deverá conter um IPv6 válido.',
    'json'                 => 'O campo deverá conter um texto JSON válido.',
    'lt'                   => [
        'numeric' => 'O campo :attribute tem que ser menor que :value.',
        'file'    => 'O campo :attribute tem que ser menor que :value kilobytes.',
        'string'  => 'O campo :attribute tem que ser menor que :value caracteres.',
        'array'   => 'O campo :attribute tem que ter menos que :value itens.',
    ],
    'lte'                  => [
        'numeric' => 'O campo :attribute tem que ser menor ou igual a :value.',
        'file'    => 'O campo :attribute tem que ser menor ou igual a :value kilobytes.',
        'string'  => 'O campo :attribute tem que ser menor ou igual a :value caracteres.',
        'array'   => 'O campo :attribute tem que ter menos ou igual a :value itens.',
    ],
    'max'                  => [
        'numeric' => 'O campo não deverá conter um valor superior a :max.',
        'file'    => 'O campo não deverá ter um tamanho superior a :max kilobytes.',
        'string'  => 'O campo não deverá conter mais de :max caracteres.',
        'array'   => 'O campo não deverá conter mais de :max elementos.',
    ],
    'mimes'                => 'O campo deverá conter um ficheiro do tipo: :values.',
    'mimetypes'            => 'O campo :attribute tem que ser um ficheiro do tipo : :values.',
    'min'                  => [
        'numeric' => 'O campo deverá ter um valor superior ou igual a :min.',
        'file'    => 'O campo deverá ter no mínimo :min kilobytes.',
        'string'  => 'O campo deverá conter no mínimo :min caracteres.',
        'array'   => 'O campo deverá conter no mínimo :min elementos.',
    ],
    'not_in'               => 'O campo contém um valor inválido.',
    'numeric'              => 'O campo deverá conter um valor numérico.',
    'present'              => 'O campo :attribute tem que estar presente.',
    'regex'                => 'O formato do valor para o campo é inválido.',
    'required'             => 'É obrigatória a indicação de um valor para o campo.',
    'required_if'          => 'É obrigatória a indicação de um valor para o campo quando o valor do campo :other é igual a :value.',
    'required_unless'      => 'É obrigatória a indicação de um valor para o campo a menos que :other esteja presente em :values.',
    'required_with'        => 'É obrigatória a indicação de um valor para o campo quando :values está presente.',
    'required_with_all'    => 'É obrigatória a indicação de um valor para o campo quando um dos :values está presente.',
    'required_without'     => 'É obrigatória a indicação de um valor para o campo quando :values não está presente.',
    'required_without_all' => 'É obrigatória a indicação de um valor para o campo quando nenhum dos :values está presente.',
    'same'                 => 'Os campos e :other deverão conter valores iguais.',
    'size'                 => [
        'numeric' => 'O campo deverá conter o valor :size.',
        'file'    => 'O campo deverá ter o tamanho de :size kilobytes.',
        'string'  => 'O campo deverá conter :size caracteres.',
        'array'   => 'O campo deverá conter :size elementos.',
    ],
    'string'               => 'O campo deverá conter texto.',
    'timezone'             => 'O campo deverá ter um fuso horário válido.',
    'unique'               => 'O valor indicado para o campo já se encontra registado.',
    'url'                  => 'O formato do URL indicado para o campo é inválido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes'           => [
        //
    ],

];
