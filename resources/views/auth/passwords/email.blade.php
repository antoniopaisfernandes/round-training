@extends('layouts.app')

@section('content')
<div class="tw-mx-auto tw-w-full md:tw-w-1/2 tw-max-w-md tw-h-full tw-flex tw-flex-col tw-justify-center">
    <v-form
        method="POST"
        action="{{ route('password.email') }}"
    >
        @csrf
        <v-card class="elevation-12">
            <v-toolbar
                color="primary"
                dark
                flat
            >
                <v-toolbar-title>{{ __('Reset Password') }}</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                @if (session('status'))
                    <div class="tw-text-green-600" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <v-text-field
                    id="email"
                    label="{{ __('E-Mail Address') }}"
                    name="email"
                    prepend-icon="mdi-account-outline"
                    type="text"
                    :dense="true"
                    hide-details="auto"
                    required
                    filled
                ></v-text-field>
                @error('email')
                    <span class="tw-text-red-600" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </v-card-text>
            <v-card-actions>
                <div class="tw-flex tw-flex-col tw-w-full">
                    <v-btn
                        class="tw-mt-6"
                        color="primary"
                        type="submit"
                    >{{ __('Send Password Reset Link') }}</v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-form>
</div>
@endsection
