@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-cherry">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                            <div class="mb-4">
                                <h5 class="card-title mb-0">Aktif Sınavda Öğrenci</h5>
                            </div>
                            <div class="row align-items-center mb-2 d-flex">
                                <div class="col-8">
                                    <h2 class="d-flex align-items-center mb-0">128</h2>
                                </div>
                                <div class="col-4 text-right">
                                    <span>12.5% <i class="fa fa-arrow-up"></i></span>
                                </div>
                            </div>
                            <div class="progress mt-1 " data-height="3" style="height: 3px;">
                                <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-blue-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="mb-4">
                                <h5 class="card-title mb-0">Bugünkü İhlaller (Violations)</h5>
                            </div>
                            <div class="row align-items-center mb-2 d-flex">
                                <div class="col-8">
                                    <h2 class="d-flex align-items-center mb-0">42</h2>
                                </div>
                            </div>
                            <div class="progress mt-1 " data-height="3" style="height: 3px;">
                                <div class="progress-bar l-bg-orange" role="progressbar" data-width="45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-green-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-edit"></i></div>
                            <div class="mb-4">
                                <h5 class="card-title mb-0">Bekleyen Manuel Puanlama</h5>
                            </div>
                            <div class="row align-items-center mb-2 d-flex">
                                <div class="col-8">
                                    <h2 class="d-flex align-items-center mb-0">15</h2>
                                </div>
                            </div>
                            <div class="progress mt-1 " data-height="3" style="height: 3px;">
                                <div class="progress-bar l-bg-cyan" role="progressbar" data-width="60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-orange-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-database"></i></div>
                            <div class="mb-4">
                                <h5 class="card-title mb-0">Mongo Ham Veri (Log)</h5>
                            </div>
                            <div class="row align-items-center mb-2 d-flex">
                                <div class="col-8">
                                    <h2 class="d-flex align-items-center mb-0">1.2GB</h2>
                                </div>
                            </div>
                            <div class="progress mt-1 " data-height="3" style="height: 3px;">
                                <div class="progress-bar l-bg-white" role="progressbar" data-width="30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Canlı Sınav Oturumları (Exam Sessions)</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>Öğrenci</th>
                                        <th>Sınav</th>
                                        <th>Cihaz/IP</th>
                                        <th>Durum</th>
                                        <th>İşlem</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Semih Gümüş</td>
                                        <td>Veritabanı Final</td>
                                        <td><small>192.168.1.1 (Windows/Chrome)</small></td>
                                        <td><div class="badge badge-success">Sınavda</div></td>
                                        <td><a href="#" class="btn btn-primary btn-sm">İzle</a></td>
                                    </tr>
                                    <tr>
                                        <td>Ahmet Yılmaz</td>
                                        <td>Python Giriş</td>
                                        <td><small>85.105.x.x (MacOS/Safari)</small></td>
                                        <td><div class="badge badge-warning">Odak Kaybı</div></td>
                                        <td><a href="#" class="btn btn-danger btn-sm">Uyarı Gönder</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Anlık İhlal Bildirimleri</h4>
                        </div>
                        <div class="card-body">
                            <div class="activities">
                                <div class="activity">
                                    <div class="activity-icon bg-danger text-white shadow-danger">
                                        <i class="fas fa-eye-slash"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job text-primary">2 Dakika Önce</span>
                                            <span class="bullet"></span>
                                            <a class="text-job" href="#">Oturum #452</a>
                                        </div>
                                        <p>Yüz tespiti başarısız. (Görüntü İşleme Logu: MongoDB_Ref_442)</p>
                                    </div>
                                </div>
                                <div class="activity">
                                    <div class="activity-icon bg-warning text-white shadow-warning">
                                        <i class="fas fa-expand"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job text-primary">5 Dakika Önce</span>
                                        </div>
                                        <p>Tam ekran modundan çıkıldı (Session Event).</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
