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
                        @php
                            // ekstra güvenlik
                            $answeredCount = $answeredCount ?? 0;
                            $correctCount  = $correctCount ?? 0;
                            $wrongCount    = $wrongCount ?? 0;
                            $pendingCount  = $pendingCount ?? 0;

                            // skor session'dan gelsin
                            $totalScore = $session->score ?? 0;
                        @endphp

                        <div class="row text-center g-3">
                            {{-- Toplam Soru Sayısı --}}
                            <div class="col-md-3">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body">
                                        <h5>Toplam Soru</h5>
                                        <h2 class="mb-0">{{ $totalQuestions }}</h2>
                                    </div>
                                </div>
                            </div>

                            {{-- Cevaplanan --}}
                            <div class="col-md-3">
                                <div class="card bg-secondary text-white h-100">
                                    <div class="card-body">
                                        <h5>Cevaplanan</h5>
                                        <h2 class="mb-0">{{ $answeredCount }}</h2>
                                    </div>
                                </div>
                            </div>

                            {{-- Doğru --}}
                            <div class="col-md-3">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body">
                                        <h5>Doğru</h5>
                                        <h2 class="mb-0">{{ $correctCount }}</h2>
                                    </div>
                                </div>
                            </div>

                            {{-- Puan --}}
                            <div class="col-md-3">
                                <div class="card bg-primary text-white h-100">
                                    <div class="card-body">
                                        <h5>Kazanılan Puan</h5>
                                        <h2 class="mb-0">{{ number_format($totalScore, 2) }}</h2>
                                    </div>
                                </div>
                            </div>

                            {{-- Yanlış --}}
                            <div class="col-md-3">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body">
                                        <h5>Yanlış</h5>
                                        <h2 class="mb-0">{{ $wrongCount }}</h2>
                                    </div>
                                </div>
                            </div>

                            {{-- Bekleyen (Açık uçlular vs) --}}
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark h-100">
                                    <div class="card-body">
                                        <h5>Bekleyen</h5>
                                        <h2 class="mb-0">{{ $pendingCount }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5>Oturum Bilgileri</h5>

                        <p>
                            <strong>Başlangıç Zamanı:</strong>
                            {{ $session->started_at ? \Illuminate\Support\Carbon::parse($session->started_at)->format('d.m.Y H:i:s') : 'Henüz Başlatılmadı' }}
                        </p>

                        <p>
                            <strong>Bitiş Zamanı:</strong>
                            {{ $session->ended_at ? \Illuminate\Support\Carbon::parse($session->ended_at)->format('d.m.Y H:i:s') : 'Devam Ediyor / Süre Dolmadı' }}
                        </p>

                        <p class="mt-4">
                            Sınavınız tamamlandı. Açık uçlu sorular varsa, değerlendirildikten sonra puanınız güncellenebilir.
                        </p>

                        <a href="{{ route('student.exams.index') }}" class="btn btn-warning mt-3">
                            Sınav Listesine Dön
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
