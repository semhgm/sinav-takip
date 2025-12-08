@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Sınav Sonuçları: {{ $exam->title }}</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Sınav Özeti</h4>
                    </div>
                    <div class="card-body">

                        <div class="row text-center">
                            {{-- Toplam Soru Sayısı --}}
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5>Toplam Soru</h5>
                                        <h2>{{ $totalQuestions }}</h2>
                                    </div>
                                </div>
                            </div>

                            {{-- Cevaplanan Soru Sayısı --}}
                            <div class="col-md-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body">
                                        <h5>Cevaplanan</h5>
                                        <h2>{{ $answeredCount }}</h2>
                                    </div>
                                </div>
                            </div>

                            {{-- Doğru Sayısı (Puanlama Sonrası Dolacak) --}}
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5>Doğru Cevap</h5>
                                        <h2>{{ $correctCount }}</h2>
                                    </div>
                                </div>
                            </div>

                            {{-- Kazanılan Puan (Puanlama Sonrası Dolacak) --}}
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5>Kazanılan Puan</h5>
                                        <h2>{{ number_format($totalScore, 2) }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>Oturum Bilgileri</h5>
                        <p>
                            <strong>Başlangıç Zamanı:</strong>
                            {{ $session->started_at ? $session->started_at->format('d.m.Y H:i:s') : 'Henüz Başlatılmadı' }}
                        </p>

                        {{-- actual_end_time'ı da kontrol edin --}}
                        <p>
                            <strong>Bitiş Zamanı:</strong>
                            {{ $session->actual_end_time ? $session->actual_end_time->format('d.m.Y H:i:s') : 'Devam Ediyor / Süre Dolmadı' }}
                        </p>
                        <p class="mt-4">
                            Sınavınız başarıyla tamamlanmıştır. Puanlama işlemi tamamlandığında sonuçlarınız güncellenecektir.
                        </p>

                        <a href="{{ route('student.exams.index') }}" class="btn btn-warning mt-3">Sınav Listesine Dön</a>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
