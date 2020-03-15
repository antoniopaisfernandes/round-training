@extends('layouts.app')

@section('content')
<div class="tw-mx-auto tw-w-full md:tw-w-1/2 tw-max-w-md">
    <v-form
        method="POST"
        action="{{ route('password.update') }}"
    >
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <v-card class="elevation-12">
            <v-toolbar
                color="primary"
                dark
                flat
            >
                <v-toolbar-title>{{ __('Reset Password') }}</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-text-field
                    id="email"
                    label="{{ __('E-Mail Address') }}"
                    name="email"
                    prepend-icon="mdi-account-outline"
                    type="email"
                    :dense="true"
                    hide-details="false"
                    required
                    filled
                    value="{{ $email ?? old('email') }}"
                ></v-text-field>
                @error('email')
                    <span class="tw-text-red-600" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                <v-text-field
                    id="password"
                    label="{{ __('Password') }}"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    :dense="true"
                    hide-details="auto"
                    required
                    filled
                ></v-text-field>
                @error('password')
                    <span class="tw-text-red-600" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                <v-text-field
                    id="password-confirm"
                    label="{{ __('Confirm Password') }}"
                    name="password_confirmation"
                    prepend-icon="mdi-lock"
                    type="password"
                    :dense="true"
                    hide-details="auto"
                    required
                    filled
                ></v-text-field>
            </v-card-text>
            <v-card-actions>
                <div class="tw-flex tw-flex-col tw-w-full">
                    <v-btn
                        class="tw-mt-6"
                        color="primary"
                        type="submit"
                    >{{ __('Reset Password') }}</v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-form>
</div>
@endsection
