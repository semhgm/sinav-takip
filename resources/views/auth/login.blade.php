<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Sınav Takip Sistemi - Giriş</title>
    <link rel="stylesheet" href="{{asset('otika/assets/css/app.min.css')}}">
    <link rel="stylesheet" href="{{asset('otika/assets/bundles/bootstrap-social/bootstrap-social.css')}}">
    <link rel="stylesheet" href="{{asset('otika/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('otika/assets/css/components.css')}}">
    <link rel="stylesheet" href="{{asset('otika/assets/css/custom.css')}}">
    <link rel='shortcut icon' type='image/x-icon' href='{{asset('otika/assets/img/favicon.ico')}}' />
</head>

<body>
<div class="loader"></div>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Giriş Yap</h4>
                        </div>
                        <div class="card-body">

                            {{-- LARAVEL HATA MESAJLARI --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        {{ $errors->first('email') }}
                                    </div>
                                </div>
                            @endif

                            {{-- FORM DÜZENLEMELERİ: action, method, @csrf eklendi --}}
                            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                                @csrf

                                <div class="form-group">
                                    <label for="email">E-posta</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" tabindex="1" required autofocus value="{{ old('email') }}">

                                    {{-- Laravel Hata Gösterimi --}}
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">Şifre</label>
                                        {{-- Şifremi Unuttum Rotasını Laravel'e uygun hale getirebiliriz (Şimdilik kaldı) --}}
                                        <div class="float-right">
                                            <a href="#" class="text-small">
                                                Şifremi Unuttum?
                                            </a>
                                        </div>
                                    </div>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" tabindex="2" required>

                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                                        <label class="custom-control-label" for="remember-me">Beni Hatırla</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Giriş Yap
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-5 text-muted text-center">
                        Hesabınız yok mu? <a href="#">Kayıt Olun</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{asset('otika/assets/js/app.min.js')}}"></script>
<script src="{{asset('otika/assets/js/scripts.js')}}"></script>
<script src="{{asset('otika/assets/js/custom.js')}}"></script>
</body>


</html>
