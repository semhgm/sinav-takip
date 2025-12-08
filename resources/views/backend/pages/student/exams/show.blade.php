@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Sınav Başlatma Ekranı</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $exam->title }} Sınav Detayları</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Temel Bilgiler</h5>
                                <table class="table table-sm table-bordered">
                                    <tr>
                                        <th>Sınav Adı</th>
                                        <td>{{ $exam->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Süre</th>
                                        <td>{{ $exam->duration_minutes }} dakika</td>
                                    </tr>
                                    <tr>
                                        <th>Soru Sayısı</th>
                                        <td>{{ $exam->questions()->count() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Açıklama</th>
                                        <td>{{ $exam->description ?? 'Açıklama mevcut değil.' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h5>Kurallar ve Gözetmenlik Ayarları</h5>
                                @php
                                    // settings alanını güvenli bir şekilde çekelim
                                    $settings = is_string($exam->settings) ? (json_decode($exam->settings, true) ?? []) : ($exam->settings ?? []);
                                @endphp

                                <ul class="list-group">
                                    <li class="list-group-item {{ $settings['proctoring_enabled'] ? 'list-group-item-danger' : 'list-group-item-success' }}">
                                        @if ($settings['proctoring_enabled'])
                                            <i class="fas fa-video text-danger"></i> **Gözetmenlik Aktif:** Kamera ve mikrofon takibi yapılacaktır.
                                        @else
                                            <i class="fas fa-check-circle text-success"></i> Gözetmenlik Pasif.
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-random"></i> Sorular: **{{ $settings['shuffle_questions'] ? 'Karışık' : 'Sıralı' }}** gösterilecektir.
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-undo"></i> Geri Dönüş: **{{ $settings['allow_back'] ? 'İzin Veriliyor' : 'İzin Verilmiyor' }}** (Cevaplanan soruya geri dönülemez).
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <hr class="my-4">

                        <form action="{{ route('student.exams.start-session', $exam->id) }}" method="POST" id="start-form">
                            @csrf
                            <p class="text-danger">**Önemli:** Sınavı başlattıktan sonra süre geri saymaya başlayacaktır. Lütfen hazır olduğunuzdan emin olun.</p>

                            <button type="submit" class="btn btn-primary btn-lg"
                                    @if ($activeSession && !$activeSession->is_completed) disabled @endif
                                    onclick="return confirm('Sınavı başlatmak istediğinizden emin misiniz? Süre hemen başlayacaktır!')">
                                Sınavı Başlat
                            </button>

                            @if ($activeSession && !$activeSession->is_completed)
                                <a href="{{ route('student.exam-live', $activeSession->id) }}" class="btn btn-warning btn-lg">
                                    Sınava Devam Et
                                </a>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
