<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Sınav Takip Sistemi - Kayıt Ol</title>
    <link rel="stylesheet" href="{{asset('otika/assets/css/app.min.css')}}">
    <link rel="stylesheet" href="{{asset('otika/assets/bundles/jquery-selectric/selectric.css')}}">
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
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Yeni Kullanıcı Kaydı</h4>
                        </div>
                        <div class="card-body">

                            {{-- LARAVEL HATA MESAJLARI --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- FORM DÜZENLEMELERİ: action, method, @csrf eklendi --}}
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="name">Ad Soyad</label>
                                        {{-- İsim alanını tek input'ta topluyoruz, Model'de sadece 'name' var --}}
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" autofocus value="{{ old('name') }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">E-posta</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="password" class="d-block">Şifre</label>
                                        <input id="password" type="password" class="form-control pwstrength @error('password') is-invalid @enderror"
                                               data-indicator="pwindicator" name="password" required>
                                        <div id="pwindicator" class="pwindicator">
                                            <div class="bar"></div>
                                            <div class="label"></div>
                                        </div>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="password_confirmation" class="d-block">Şifre Tekrarı</label>
                                        {{-- Laravel'de onaylama için kullanılan alan adı: password_confirmation --}}
                                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>

                                {{-- Kullanım Koşulları (Opsiyonel) --}}
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="agree" class="custom-control-input" id="agree">
                                        <label class="custom-control-label" for="agree">Kullanım koşullarını kabul ediyorum.</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Kayıt Ol
                                    </button>
                                </div>
                            </form>

                        </div>
                        <div class="mb-4 text-muted text-center">
                            Zaten kayıtlı mısınız? <a href="{{ route('login') }}">Giriş Yapın</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{asset('otika/assets/js/app.min.js')}}"></script>
<script src="{{asset('otika/assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js')}}"></script>
<script src="{{asset('otika/assets/bundles/jquery-selectric/jquery.selectric.min.js')}}"></script>
<script src="{{asset('otika/assets/js/page/auth-register.js')}}"></script>
<script src="{{asset('otika/assets/js/scripts.js')}}"></script>
<script src="{{asset('otika/assets/js/custom.js')}}"></script>
</body>
</html>
