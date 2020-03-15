@extends('layouts.app')

@section('content')
<v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
        <v-col class="text-center">
            <div class="tw-mx-auto tw-w-full md:tw-w-1/2 tw-max-w-md">
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
                                @if (Route::has('password.request'))
                                    <div class="tw-mt-6">
                                        <a href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </div>
        </v-col>
    </v-row>
</v-container>
@endsection
