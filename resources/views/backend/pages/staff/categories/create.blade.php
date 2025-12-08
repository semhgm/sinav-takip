@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Yeni Kategori Oluştur</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Kategori Adı</label>
                            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Kategoriyi Kaydet</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
