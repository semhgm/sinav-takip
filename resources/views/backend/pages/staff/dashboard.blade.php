@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="row ">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Aktif Oturum</h5>
                                            <h2 class="mb-3 font-18">258</h2>
                                            <p class="mb-0"><span class="col-green">18</span> Yeni Katılım</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('otika/assets/img/banner/1.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Kritik İhlal</h5>
                                            <h2 class="mb-3 font-18 text-danger">14</h2>
                                            <p class="mb-0"><span class="col-orange">Anlık</span> Bildirim</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('otika/assets/img/banner/2.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Bekleyen Puan</h5>
                                            <h2 class="mb-3 font-18">85</h2>
                                            <p class="mb-0"><span class="col-purple">Manuel</span> Grading</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('otika/assets/img/banner/3.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Sistem Sağlığı</h5>
                                            <h2 class="mb-3 font-18">99.2%</h2>
                                            <p class="mb-0"><span class="col-green">Stabil</span> Çalışıyor</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('otika/assets/img/banner/4.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Canlı Oturum Kontrol Paneli</h4>
                            <div class="card-header-form">
                                <form>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Öğrenci Ara...">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th class="text-center">Profil</th>
                                        <th>Öğrenci Adı</th>
                                        <th>Sınav</th>
                                        <th>İlerleme</th>
                                        <th>Son Sinyal (Heartbeat)</th>
                                        <th>Aksiyon</th>
                                    </tr>
                                    <tr>
                                        <td class="p-0 text-center">
                                            <img alt="image" src="{{ asset('otika/assets/img/users/user-1.png') }}" class="rounded-circle" width="35">
                                        </td>
                                        <td>Semih Gümüş</td>
                                        <td>İleri Seviye PHP</td>
                                        <td class="align-middle">
                                            <div class="progress" data-height="4" data-toggle="tooltip" title="70%">
                                                <div class="progress-bar bg-success" data-width="70%"></div>
                                            </div>
                                        </td>
                                        <td><div class="badge badge-success">0.5s Önce</div></td>
                                        <td><a href="#" class="btn btn-outline-primary btn-sm">Detay</a></td>
                                    </tr>
                                    <tr>
                                        <td class="p-0 text-center">
                                            <img alt="image" src="{{ asset('otika/assets/img/users/user-2.png') }}" class="rounded-circle" width="35">
                                        </td>
                                        <td>Ayşe Yılmaz</td>
                                        <td>Veritabanı Final</td>
                                        <td class="align-middle">
                                            <div class="progress" data-height="4" data-toggle="tooltip" title="25%">
                                                <div class="progress-bar bg-orange" data-width="25%"></div>
                                            </div>
                                        </td>
                                        <td><div class="badge badge-warning">15s Önce</div></td>
                                        <td><a href="#" class="btn btn-outline-danger btn-sm">Gözlemle</a></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Mongo Senkronizasyon (Correlation)</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="text-small float-right font-weight-bold text-muted">2,100 Paket</div>
                                <div class="font-weight-bold mb-1">Görüntü İşleme Verisi</div>
                                <div class="progress" data-height="3">
                                    <div class="progress-bar bg-cyan" role="progressbar" data-width="80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="text-small float-right font-weight-bold text-muted">120 Olay</div>
                                <div class="font-weight-bold mb-1">Tarayıcı Olayları (Events)</div>
                                <div class="progress" data-height="3">
                                    <div class="progress-bar bg-purple" role="progressbar" data-width="45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="text-small float-right font-weight-bold text-muted">Tamamlandı</div>
                                <div class="font-weight-bold mb-1">Session Summary Oluşturma</div>
                                <div class="progress" data-height="3">
                                    <div class="progress-bar bg-green" role="progressbar" data-width="100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card gradient-bottom">
                        <div class="card-header">
                            <h4>Son İhlal Analizi</h4>
                        </div>
                        <div class="card-body" id="top-5-scroll" height="315">
                            <ul class="list-unstyled list-unstyled-border">
                                <li class="media">
                                    <img class="mr-3 rounded-circle" width="50" src="{{ asset('otika/assets/img/users/user-5.png') }}" alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right text-primary">Az Önce</div>
                                        <div class="media-title">Kamera İhlali</div>
                                        <span class="text-small text-muted">Öğrenci: Mehmet Can <br> Sebep: Birden fazla yüz tespiti.</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer pt-3 d-flex justify-content-center">
                            <div class="budget-price justify-content-center">
                                <div class="budget-price-square bg-danger" data-width="20"></div>
                                <div class="budget-price-label">Kritik Limit</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
