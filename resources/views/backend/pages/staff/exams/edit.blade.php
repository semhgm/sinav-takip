@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Sınavı Düzenle: {{ $exam->title }}</h4>
                </div>
                <div class="card-body">

                    <form action="{{ route('staff.exams.update', $exam->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5>Temel Bilgiler</h5>

                        <div class="form-group">
                            <label for="title">Sınav Adı</label>
                            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $exam->title) }}">
                        </div>
                        <div class="form-group">
                            <label for="duration_minutes">Sınav Süresi (Dakika)</label>
                            <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" required value="{{ old('duration_minutes', $exam->duration_minutes) }}">
                            @error('duration_minutes') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <h5>Sınav Kuralları (Settings)</h5>

                        {{-- ✨ DÜZELTİLMİŞ BLOK: Sınav ayarlarını güvenli bir şekilde diziye dönüştürüyoruz. --}}
                        @php
                            // Eğer $exam->settings boş string ('') ise boş diziye [] çeviriyoruz.
                            $examSettingsArray = is_string($exam->settings) ? (json_decode($exam->settings, true) ?? []) : ($exam->settings ?? []);

                            $defaultSettings = [
                                'shuffle_questions'  => false,
                                'proctoring_enabled' => false,
                                'allow_back'         => false,
                                'shuffle_options'    => false
                            ];

                            $currentSettings = array_merge($defaultSettings, $examSettingsArray);
                        @endphp
                        @foreach ($examRules as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="settings[{{ $key }}]" value="1" id="{{ $key }}"
                                    @checked($currentSettings[$key] ?? false)>
                                <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-warning mt-3">Temel Ayarları Güncelle</button>
                    </form>

                    <hr class="my-5">

                    {{-- Soru Atama Formu (Bu kısım zaten $exam->settings kullanmıyor, sorunsuz) --}}
                    <form action="{{ route('staff.exams.update', $exam->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action_type" value="questions_update">

                        <h5>Soru Havuzundan Ekleme</h5>
                        <p class="text-muted">Sınava eklenecek soruları seçin ve sıralarını belirleyin.</p>

                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 50px;">Ekle</th>
                                <th>Soru Metni</th>
                                <th style="width: 100px;">Sıra (Order)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($availableQuestions as $question)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="questions_to_attach[{{ $question->id }}][attach]" value="1"
                                            @checked(in_array($question->id, $attachedQuestionIds))>
                                    </td>
                                    <td>
                                        <small>Kategori: {{ $question->category->name ?? 'Yok' }}</small><br>
                                        {{ Str::limit(strip_tags($question->text), 100) }}
                                    </td>
                                    <td>
                                        @php
                                            // Eğer soru zaten ekliyse, mevcut sırasını çekelim.
                                            $currentOrder = $exam->questions->firstWhere('id', $question->id) ? $exam->questions->firstWhere('id', $question->id)->pivot->order : null;
                                        @endphp
                                        <input type="number" name="questions_to_attach[{{ $question->id }}][order]"
                                               value="{{ $currentOrder ?? $loop->iteration }}"
                                               class="form-control form-control-sm text-center">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary mt-3">Seçilen Soruları Sınava Ata ve Sırala</button>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
