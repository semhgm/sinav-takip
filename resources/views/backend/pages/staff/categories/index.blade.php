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
                                <h4>Soru Kategorileri</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('staff.categories.create') }}" class="btn btn-primary">Yeni Kategori Ekle</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kategori Adı</th>
                                        <th>Oluşturulma Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->created_at->format('d.m.Y') }}</td>
                                            <td>
                                                <a href="{{ route('staff.categories.edit', $category->id) }}" class="btn btn-sm btn-info">Düzenle</a>
                                                {{-- Silme formu --}}
                                                <form action="{{ route('staff.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Kategoriyi silmek, ilgili soruların kategorisini boşaltabilir. Emin misiniz?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Henüz hiç kategori eklenmedi.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
