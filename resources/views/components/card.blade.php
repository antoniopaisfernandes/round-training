@props(['courses', 'image', 'link', 'titlePrefix' => null])

<div class="tw-mx-auto tw-flex tw-flex-col tw-justify-between tw-gap-2 tw-bg-white tw-px-8 tw-py-4">
    <img
        class="tw-h-48 tw-w-48 tw-rounded-lg tw-object-cover"
        src="{{ $image }}"
    ></img>

    @if ($titlePrefix)
        <v-card-title>{{ $titlePrefix }}: {{ $courses->count() }}</v-card-title>
    @endif

    @if ($courses->count())
        <v-card-text class="text--primary">
            @foreach ($courses as $programEdition)
                <div class="tw-text-left">{{ $programEdition->full_name }}</div>
            @endforeach
        </v-card-text>

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
</div>
