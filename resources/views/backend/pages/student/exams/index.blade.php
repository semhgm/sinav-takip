@extends('backend.layout.app') // Öğrenci template'inizi çağırın

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Atanmış Sınavlarım</h1>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Girebileceğiniz Sınavlar</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sınav Adı</th>
                                    <th>Süre</th>
                                    <th>Soru Sayısı</th>
                                    <th>Durum</th>
                                    <th>İşlem</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($assignedExams as $exam)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $exam->title }}</td>
                                        <td>{{ $exam->duration_minutes }} dakika</td>
                                        <td>
                                            {{-- İlişkiyi kullanarak soru sayısını gösterir --}}
                                            {{ $exam->questions_count }} soru
                                        </td>
                                        <td>
                                            {{-- Öğrencinin bu sınavda aktif veya tamamlanmış bir oturumu var mı kontrol edilebilir --}}
                                            @php
                                                $session = $exam->sessions()->where('user_id', Auth::id())->first();
                                            @endphp

                                            @if ($session && $session->is_completed)
                                                <span class="badge badge-success">Tamamlandı</span>
                                            @elseif ($session && $session->status == 'active')
                                                <span class="badge badge-warning">Aktif (Devam Ediyor)</span>
                                            @else
                                                <span class="badge badge-info">Atandı</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($session && $session->is_completed)
                                                <a href="{{ route('student.exams.results', $exam->id) }}" class="btn btn-sm btn-secondary">Sonuçları Gör</a>
                                            @elseif ($session && $session->status == 'active')
                                                <a href="{{ route('student.exam-live', $session->id) }}" class="btn btn-sm btn-warning">Sınava Devam Et</a>
                                            @else
                                                {{-- show metodu, sınav detaylarını ve "Başlat" butonunu gösterecek --}}
                                                <a href="{{ route('student.exams.show', $exam->id) }}" class="btn btn-sm btn-primary">Sınavı Başlat</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Size atanmış aktif bir sınav bulunmamaktadır.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
