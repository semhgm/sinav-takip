@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Atanan Sınavlarım</h4>
                            </div>
                            <div class="card-body">4</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Başarı Ortalaması</h4>
                            </div>
                            <div class="card-body">82.5</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-warning">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Toplam Çözülen Soru</h4>
                            </div>
                            <div class="card-body">245</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7 col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h4>Girebileceğim Aktif Sınavlar</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">
                                <li class="media">
                                    <img class="mr-3 rounded-circle" width="50" src="{{ asset('otika/assets/img/products/product-1.png') }}" alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right"><a href="#" class="btn btn-outline-primary">Sınava Başla</a></div>
                                        <div class="media-title">Veritabanı Tasarımı - Ara Sınav</div>
                                        <span class="text-small text-muted">Süre: 45 Dakika | Kategori: Bilgisayar Müh.</span>
                                    </div>
                                </li>
                                <li class="media">
                                    <img class="mr-3 rounded-circle" width="50" src="{{ asset('otika/assets/img/products/product-2.png') }}" alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right"><span class="badge badge-secondary">Henüz Aktif Değil</span></div>
                                        <div class="media-title">Yapay Zekaya Giriş</div>
                                        <span class="text-small text-muted">Başlangıç: Yarın 09:00</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>Son Sınav Performanslarım</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="text-small float-right font-weight-bold text-muted">95</div>
                                <div class="font-weight-bold mb-1">Matematik Final</div>
                                <div class="progress" data-height="3">
                                    <div class="progress-bar bg-success" role="progressbar" data-width="95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="text-small float-right font-weight-bold text-muted">65</div>
                                <div class="font-weight-bold mb-1">Algoritmalar</div>
                                <div class="progress" data-height="3">
                                    <div class="progress-bar bg-warning" role="progressbar" data-width="65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
