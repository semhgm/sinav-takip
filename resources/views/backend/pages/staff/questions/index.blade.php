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
                                <h4>Soru Havuzu</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('staff.questions.create') }}" class="btn btn-primary">Yeni Soru Ekle</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="question-table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Soru Metni</th>
                                            <th>Kategori</th>
                                            <th>Tipi</th>
                                            <th>Doğru Cevap</th>
                                            <th>İşlemler</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($questions as $question)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ Str::limit(strip_tags($question->text), 80) }}</td>
                                                <td>{{ $question->category->name ?? 'Kategori Yok' }}</td>
                                                <td>{{ $question->type === 'multiple_choice' ? 'Çoktan Seçmeli' : 'Açık Uçlu' }}</td>
                                                <td>{{ $question->correct_option }}</td>
                                                <td>
                                                    <a href="{{ route('staff.questions.edit', $question->id) }}" class="btn btn-sm btn-info">Düzenle</a>
                                                    {{-- Silme butonu --}}
                                                    {{-- <form action="{{ route('staff.questions.destroy', $question->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</button>
                                                    </form> --}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Soru havuzunda henüz hiç soru bulunmamaktadır.</td>
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
