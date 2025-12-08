@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Sınav Listesi</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('staff.exams.create') }}" class="btn btn-primary">Yeni Sınav Ekle</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="exam-table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sınav Adı</th>
                                            <th>Süre (Dakika)</th>
                                            <th>Soru Sayısı</th>
                                            <th>Durum</th>
                                            <th>Oluşturulma Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($exams as $exam)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $exam->title }}</td>
                                                <td>{{ $exam->duration_minutes }}</td>
                                                <td>
                                                    {{-- İlişkiyi kullanarak o sınava eklenen soru sayısını gösterir --}}
                                                    {{ $exam->questions->count() }}
                                                </td>
                                                <td>
                                                    @if ($exam->questions->count() > 0)
                                                        <span class="badge badge-success">Sorular Tamamlandı</span>
                                                    @else
                                                        <span class="badge badge-warning">Soru Bekleniyor</span>
                                                    @endif
                                                </td>
                                                <td>{{ $exam->created_at->format('d.m.Y H:i') }}</td>
                                                <td>
                                                    {{-- Soru Ekleme Linki (Modül 4'e hazırlık) --}}
                                                    <a href="{{ route('staff.exams.edit', $exam->id) }}" class="btn btn-sm btn-info">Düzenle</a>
                                                    <a href="{{ route('staff.questions.create', $exam->id) }}" class="btn btn-sm btn-success">Soruları Ekle/Gör</a>
                                                    <a href="{{ route('staff.exams.edit', $exam->id) }}" class="btn btn-sm btn-info">Düzenle</a>
                                                    <a href="{{ route('staff.exams.assign', $exam->id) }}" class="btn btn-sm btn-primary">Öğrenci Ata</a>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Henüz oluşturulmuş bir sınav bulunmamaktadır.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
