<v-card
    class="mx-auto"
    max-width="400"
>
    <v-img
        class="white--text align-end"
        height="200px"
        src="{{ $image }}"
    >
    @if ($titlePrefix)
        <v-card-title>{{ $titlePrefix }}: {{ $courses->count() }}</v-card-title>
    @endif
    </v-img>

    <v-card-text class="text--primary">
        @foreach ($courses as $programEdition)
            <div class="tw-text-left">{{ $programEdition->full_name }}</div>
        @endforeach
    </v-card-text>

    @if ($courses->count())
        <v-card-actions>
            <v-btn
                color="primary"
                text
                href="{{ $link }}"
            >
                {{ __('app.details') }}
            </v-btn>
        </v-card-actions>
    @endif
</v-card>
