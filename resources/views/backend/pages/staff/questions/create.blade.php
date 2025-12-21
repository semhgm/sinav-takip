@extends('backend.layout.app') // Ana template'inizi çağırın

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Yeni Soru Oluştur</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.questions.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Kategori Seçiniz</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="type">Soru Tipi</label>
                            <select name="type" id="question_type" class="form-control" required>
                                @foreach ($questionTypes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="text">Soru Metni</label>
                            <textarea name="text" id="question_text" class="form-control"></textarea>
                        </div>

                        <div id="dynamic_options_area">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="points">Soru Puan Değeri</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <input type="number" step="0.5" name="points" id="points"
                                       class="form-control @error('points') is-invalid @enderror"
                                       value="{{ old('points', 10) }}" placeholder="Örn: 10">
                                @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Bu soru doğru cevaplandığında öğrencinin alacağı ham puan.</small>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Soruyu Kaydet</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    {{-- **Önemli: JavaScript ile Dinamik Seçenek Alanı Yönetimi** --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('question_type');
            const optionsArea = document.getElementById('dynamic_options_area');

            function renderOptions(type) {
                optionsArea.innerHTML = '';

                if (type === 'multiple_choice') {
                    optionsArea.innerHTML = `
            <hr>
            <h5>Seçenekler ve Doğru Cevap</h5>
            <div class="row">
                <div class="form-group col-6">
                    <label>Seçenek A</label><input type="text" name="options[A]" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label>Seçenek B</label><input type="text" name="options[B]" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label>Seçenek C</label><input type="text" name="options[C]" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label>Seçenek D</label><input type="text" name="options[D]" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label for="correct_option">Doğru Cevap Anahtarı</label>
                <select name="correct_option" class="form-control" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
        `;
                } else if (type === 'open_ended') {
                    optionsArea.innerHTML = `
            <hr>
            <h5>Beklenen Cevap</h5>
            <div class="form-group">
                <label for="correct_option">Beklenen Cevap Metni (Referans)</label>
                <textarea name="correct_option" class="form-control" placeholder="Öğrencinin vermesi beklenen cevabı girin" required></textarea>
            </div>
        `;
                }
            }

            // Başlangıçta ve tip değiştiğinde seçenekleri yükle
            renderOptions(typeSelect.value);
            typeSelect.addEventListener('change', (e) => renderOptions(e.target.value));
        });
    </script>
@endsection
