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
                optionsArea.innerHTML = ''; // Önceki içeriği temizle

                if (type === 'multiple_choice') {
                    optionsArea.innerHTML = `
                <hr>
                <h5>Seçenekler ve Doğru Cevap</h5>
                <div class="form-group">
                    <label>Seçenek A</label><input type="text" name="options[A]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Seçenek B</label><input type="text" name="options[B]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Seçenek C</label><input type="text" name="options[C]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Seçenek D</label><input type="text" name="options[D]" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="correct_option">Doğru Cevap (A/B/C/D)</label>
                    <input type="text" name="answer_key" class="form-control" placeholder="Örn: A" required>
                </div>
            `;
                } else if (type === 'open_ended') {
                    optionsArea.innerHTML = `
                <hr>
                <h5>Beklenen Cevap</h5>
                <div class="form-group">
                    <label for="answer_key">Beklenen Cevap Metni</label>
                    <textarea name="correct_option" class="form-control" placeholder="Öğrencinin vermesi beklenen kısa cevabı girin"></textarea>
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
