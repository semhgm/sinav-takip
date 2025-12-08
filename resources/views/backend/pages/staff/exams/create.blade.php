@extends('backend.layout.app') // Ana template'inizi çağırın
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Yeni Sınav Tanımla</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.exams.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Sınav Adı</label>
                            <input type="text" name="title" id="name" class="form-control" required value="{{ old('title') }}">
                        </div>

                        <div class="form-group">
                            <label for="duration_minutes">Sınav Süresi (Dakika)</label>
                            <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" required value="{{ old('duration_minutes') }}">
                        </div>
                        <hr>

                        <h5>Sınav Kuralları ve Ayarları</h5>
                        <p class="text-muted">Sınavın uygulanış şeklini belirleyin.</p>

                        @foreach ($examRules as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="settings[{{ $key }}]" value="1" id="{{ $key }}">
                                <label class="form-check-label" for="{{ $key }}">
                                    {{ $label }}
                                </label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-success mt-4">Sınavı Oluştur ve Ayarları Kaydet</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection
