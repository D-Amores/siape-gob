@extends('layouts.auth_layout')
@section('title', 'Iniciar Sesión')
@section('content')
<div class="card-body">
    <a href="login" class="text-nowrap logo-img text-center d-block mb-5 w-100">
        <img src="{{ asset('modernize/assets/images/logos/dark-logo.svg') }}" class="dark-logo" alt="Logo-Dark" />
        <img src="{{ asset('modernize/assets/images/logos/light-logo.svg') }}" class="light-logo" alt="Logo-light" />
    </a>
    <h3 class="text-center mb-4">Iniciar Sesión</h3>
    <div id="alert" class="alert alert-danger d-none" role="alert"></div>

    <form id="loginForm">
        <div class="mb-3">
            <label for="username" class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" id="username" aria-describedby="emailHelp" required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" required>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                <label class="form-check-label text-dark" for="flexCheckChecked">
                    Recordar este Dispositivo
                </label>
            </div>

            {{-- <a class="text-primary fw-medium"
                                            href="{{ asset('modernize/main/authentication-forgot-password.html') }}">¿Olvidaste
                                            tu Contraseña?</a> --}}
        </div>
        <button type="button" id="btnSubmit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Iniciar Sesión</button>
        <div class="d-flex align-items-center justify-content-center">
            <p class="fs-4 mb-0 fw-medium">SIAPE - 2025</p>
            {{-- <a class="text-primary fw-medium ms-2"
                                            href="{{ asset('modernize/main/authentication-register.html') }}">Crear una
                                            cuenta</a> --}}
        </div>
    </form>
</div>
@endsection
@section('scripts')
<script>
    const vURI = window.location.origin + '/login';
</script>
<script src="{{ asset('js/auth/login.js') }}"></script>
@endsection

