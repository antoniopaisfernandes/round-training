@extends('layouts.app')

@section('content')
<div class="tw-mx-auto tw-w-full md:tw-w-1/2 tw-max-w-md tw-h-full tw-flex tw-flex-col tw-justify-center">
    <v-form
        method="POST"
        action="{{ route('login') }}"
        @keyup.enter.native="submit"
    >
        @csrf
        <v-card class="elevation-12">
            <v-toolbar
                color="primary"
                dark
                flat
            >
                <v-toolbar-title>Login</v-toolbar-title>
            </v-toolbar>

            <v-card-text>
                <v-text-field
                    id="email"
                    label="{{ __('E-Mail Address') }}"
                    name="email"
                    prepend-icon="mdi-account-outline"
                    type="text"
                    required
                    :rules="[
                        value => !!value || '{{ __('validation.required') }}',
                    ]"
                ></v-text-field>
                @error('email')
                    <div class="tw-w-full tw-text-center tw-text-red-600 tw-mb-2">
                        {{ $message }}
                    </div>
                @enderror
                <v-text-field
                    id="password"
                    label="{{ __('Password') }}"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    required
                    :rules="[
                        value => !!value || '{{ __('validation.required') }}',
                    ]"
                    ></v-text-field>
                @error('password')
                    <div class="tw-w-full tw-text-center tw-text-red-600 tw-mb-2">
                        {{ $message }}
                    </div>
                @enderror
            </v-card-text>

            <v-card-actions>
                <div class="tw-flex tw-flex-col tw-w-full">
                    <div class="tw-ml-auto">
                        <v-checkbox
                            class="tw-mt-0"
                            name="remember"
                            id="remember"
                            {{ old('remember') ? 'checked' : '' }}
                            label="{{ __('Remember Me') }}"
                            hide-details="true"
                        ></v-checkbox>
                    </div>

                    <v-btn
                        class="tw-mt-6"
                        color="primary"
                        type="submit"
                    >{{ __('Login') }}</v-btn>

                    <div class="tw-mt-6 tw-w-full tw-text-center">
                        <a href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                </div>
            </v-card-actions>
        </v-card>
    </v-form>
</div>
@endsection
