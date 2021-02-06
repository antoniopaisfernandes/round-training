@extends('layouts.app')

@section('content')
<v-container class="fill-height" fluid>
    <div class="tw-w-full lg:tw-grid lg:tw-grid-cols-3 lg:tw-gap-4">
        <x-card
            :courses="$ended"
            titlePrefix="Ended"
            image="/images/random_cards/5.jfif"
            link="/program-editions?filter[status]=ended"
        ></x-card>

        <x-card
            :courses="$active"
            titlePrefix="Active"
            image="/images/random_cards/3.jfif"
            link="/program-editions?filter[status]=active"
        ></x-card>

        <x-card
            :courses="$future"
            titlePrefix="In the future"
            image="/images/random_cards/0.jfif"
            link="/program-editions?filter[status]=future"
        ></x-card>
    </div>
</v-container>
@endsection
