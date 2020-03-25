@extends('layouts.app')

@section('content')
<div class="tw-mx-auto tw-w-full md:tw-w-1/2 tw-max-w-md tw-h-full tw-flex tw-flex-col tw-justify-center">
    <v-form
        method="POST"
        action="{{ route('login') }}"
        {{-- @keyup.enter.native="submit" --}}
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
                    @error('email')
                        error-messages="{{ $message }}"
                    @enderror
                ></v-text-field>
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
                    @error('password')
                        error-messages="{{ $message }}"
                    @enderror
                    ></v-text-field>
            </v-card-text>

            <v-card-actions style="padding: 16px;">
                <div class="tw-flex tw-flex-col tw-w-full">
                    <v-checkbox
                        class="tw-mt-0"
                        name="remember"
                        id="remember"
                        {{ old('remember') ? 'checked' : '' }}
                        label="{{ __('Remember Me') }}"
                        hide-details="true"
                    ></v-checkbox>
                    <div class="tw-w-full tw-flex tw-items-center">
                        <div class="tw-flex-1 tw-flex">
                            <a class="tw-text-sm tw-ml-auto tw-underline" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                        <v-btn
                            class="tw-ml-6"
                            color="primary"
                            type="submit"
                        >{{ __('Login') }}</v-btn>
                    </div>
                </div>
            </v-card-actions>
        </v-card>
    </v-form>
</div>
@endsection
