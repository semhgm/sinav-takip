@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="container-fluid py-3">
                <div class="row">

                    <div class="col-md-3">
                        <div class="card bg-dark text-white sticky-top">
                            <div class="card-body text-center">
                                <h5>Kalan Süre</h5>
                                {{-- **ZAMANLAYICI (JS ile doldurulacak)** --}}
                                <h2 id="countdown" class="text-warning">Yükleniyor...</h2>
                                <hr>

                                <h5>Soru Navigasyonu</h5>
                                {{-- **SORU NAVİGASYONU (JS ile doldurulacak)** --}}
                                <div id="question-navigator" class="btn-group d-flex flex-wrap">

                                    @foreach ($questions as $index => $question)
                                        <button type="button"
                                                class="btn btn-outline-secondary btn-sm m-1 nav-btn"
                                                data-index="{{ $index + 1 }}"
                                                style="width: 35px;">
                                            {{ $index + 1 }}
                                        </button>
                                    @endforeach
                                </div>

                                <hr>
                                <form id="finish-form" action="{{ route('student.exams.finish', $session->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-block">
                                        Sınavı Bitir ve Gönder
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <h5>Soru <span id="current-q-number">1</span> / {{ $questions->count() }}</h5>
                            </div>
                            <div class="card-body">

                                @foreach ($questions as $index => $question)
                                    @php
                                        $savedAnswer = $studentAnswers->get($question->id);
                                        $questionIndex = $index + 1;

                                        // options string (json) geliyorsa array'e çevir
                                        $options = $question->options;
                                        if (is_string($options)) {
                                            $options = json_decode($options, true);
                                        }
                                        if (!is_array($options)) {
                                            $options = [];
                                        }
                                    @endphp

                                    <div class="question-container"
                                         data-question-id="{{ $question->id }}"
                                         data-index="{{ $questionIndex }}"
                                         style="{{ $questionIndex == 1 ? '' : 'display:none;' }}">

                                        <div class="question-text mb-4">
                                            {!! $question->text !!}
                                        </div>

                                        <div class="answer-area" data-type="{{ $question->type }}">
                                            @if ($question->type === 'multiple_choice')
                                                <h6>Seçenekler:</h6>

                                                @foreach ($options as $key => $optionText)
                                                    <div class="form-check">
                                                        <input class="form-check-input answer-input" type="radio"
                                                               name="answer_{{ $question->id }}"
                                                               id="q_{{ $question->id }}_opt_{{ $key }}"
                                                               value="{{ $key }}"
                                                               data-question-id="{{ $question->id }}"
                                                            @checked($savedAnswer && $savedAnswer->answer_text === (string)$key)>

                                                        <label class="form-check-label" for="q_{{ $question->id }}_opt_{{ $key }}">
                                                            <strong>{{ $key }})</strong> {{ $optionText }}
                                                        </label>
                                                    </div>
                                                @endforeach

                                            @elseif ($question->type === 'open_ended')
                                                <h6>Cevabınız:</h6>
                                                <textarea class="form-control answer-input" rows="5"
                                                          data-question-id="{{ $question->id }}">{{ $savedAnswer->answer_text ?? '' }}</textarea>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between mt-4">
                                    <button id="prev-btn" class="btn btn-secondary">Önceki Soru</button>
                                    <button id="next-btn" class="btn btn-primary">Sonraki Soru</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    </div>
    <script>
        // Güvenli kontrol: Eğer end_time null ise, mevcut zamanı 0 olarak kabul eden bir yedek değer kullan.
        @php
            use Illuminate\Support\Carbon;

                // ended_at bir string gelse bile Carbon ile nesneye çevirip güvenli hale getiriyoruz
                $endTime = $session->ended_at ? Carbon::parse($session->ended_at) : now()->addMinutes($exam->duration_minutes);
                $endTimeString = $endTime->toIso8601String();
                @endphp

        // Blade'deki JS değişkenini düzeltin
        window.PROCTOR_TOKEN = "{{ $session->proctor_token }}";
        const AGENT_BASE = "http://127.0.0.1:5454";

        function stopAgent() {
            try {
                const payload = JSON.stringify({ token: window.PROCTOR_TOKEN });
                const blob = new Blob([payload], { type: "application/json" });
                navigator.sendBeacon(`${AGENT_BASE}/stop`, blob);
            } catch (e) {}
        }
        async function ensureAgentRunning() {
            try {
                // Agent var mı?
                const ping = await fetch("http://127.0.0.1:5454/ping", {
                    method: "GET",
                    cache: "no-store"
                });

                if (!ping.ok) throw new Error();

                // Token gönder → agent başlasın
                const start = await fetch("http://127.0.0.1:5454/start", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({
                        token: window.PROCTOR_TOKEN
                    })
                });

                if (!start.ok) throw new Error();

                console.log("✅ Proctor Agent aktif");

            } catch (e) {
                alert(
                    "Gözetim yazılımı çalışmıyor.\n\n" +
                    "Lütfen agent’ı başlatın ve sayfayı yenileyin."
                );
                window.location.href = "{{ route('student.exams.index') }}";
            }
        }

        document.addEventListener("DOMContentLoaded", ensureAgentRunning);
        document.addEventListener('DOMContentLoaded', function () {
            // API Uç Noktaları ve Sınav Bilgileri
            const saveAnswerUrl = "{{ route('student.exam-save-answer', $session->id) }}";
            const finishExamUrl = "{{ route('student.exams.finish', $session->id) }}";
            const endTime = new Date("{{ $endTimeString }}");
            const durationMinutes = {{ $exam->duration_minutes }};
            let isSubmitting = false; // Formun gönderilip gönderilmediğini takip eder


            let currentQuestionIndex = 1;
            const totalQuestions = {{ $questions->count() }};
            const questions = document.querySelectorAll('.question-container');
            const nextBtn = document.getElementById('next-btn');
            const prevBtn = document.getElementById('prev-btn');
            const countdownElement = document.getElementById('countdown');
            const currentQNumberElement = document.getElementById('current-q-number');
            const answerInputs = document.querySelectorAll('.answer-input');


            const preventExit = (e) => {
                if (!isSubmitting) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            };
            window.addEventListener('beforeunload', preventExit);
            window.addEventListener("pagehide", stopAgent);   // mobil/modern
            window.addEventListener("beforeunload", stopAgent); // klasik

            const finishForm = document.getElementById('finish-form');

            if (finishForm) {
                finishForm.addEventListener('submit', function () {
                    stopAgent(); // 👈 NET VE GARANTİLİ
                });
            }


            function showQuestion(index) {
                if (index < 1 || index > totalQuestions) return;

                questions.forEach(q => q.style.display = 'none');
                document.querySelector(`.question-container[data-index="${index}"]`).style.display = 'block';

                currentQuestionIndex = index;
                currentQNumberElement.textContent = index;

                // Navigasyon butonlarını güncelle
                prevBtn.disabled = index === 1;
                nextBtn.disabled = index === totalQuestions;

                // Soru navigasyon butonlarını (eğer eklediyseniz) da güncellemek gerekir (Örn: aktif butonu renklendir)
            }

            nextBtn.addEventListener('click', () => showQuestion(currentQuestionIndex + 1));
            prevBtn.addEventListener('click', () => showQuestion(currentQuestionIndex - 1));

            // Başlangıçta ilk soruyu göster
            showQuestion(1);

            // --- 2. AJAX Cevap Kaydetme Mantığı ---

            async function saveAnswer(questionId, answerText) {
                try {
                    const response = await fetch(saveAnswerUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            answer_text: answerText,
                        })
                    });

                    const result = await response.json();
                    if(result.success) {
                        console.log("Soru " + questionId + " kaydedildi.");
                        // Opsiyonel: Soru navigasyon butonunu yeşil yap
                        document.querySelector(`.nav-btn[data-index="${currentQuestionIndex}"]`).classList.replace('btn-outline-secondary', 'btn-success');
                    }
                } catch (error) {
                    console.error("Cevap gönderilemedi:", error);
                }
            }

            // Giriş/Değişiklik Dinleyicileri
            answerInputs.forEach(input => {
                if (input.type === 'radio') {
                    input.addEventListener('change', (e) => {
                        const qId = e.target.dataset.questionId;
                        const answer = e.target.value;
                        saveAnswer(qId, answer);
                    });
                } else if (input.tagName === 'TEXTAREA') {
                    // Açık uçlu sorular için debounce (kullanıcının yazmayı bırakmasını bekleme) kullan
                    let timeout = null;
                    input.addEventListener('input', (e) => {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            const qId = e.target.dataset.questionId;
                            const answer = e.target.value;
                            saveAnswer(qId, answer);
                        }, 1000); // 1 saniye bekledikten sonra kaydet
                    });
                }
            });
            if (finishForm) {
                finishForm.addEventListener('submit', function () {
                    stopAgent(); // 👈 NET VE GARANTİLİ
                });
            }

            // --- 3. Zamanlayıcı Mantığı ---

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = endTime.getTime() - now;

                // Zaman formatı hesaplama
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Görüntüyü güncelle
                countdownElement.innerHTML = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                // Süre dolduğunda
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = "SÜRE DOLDU!";
                    // Sınavı otomatik olarak bitir
                    alert("Sınav süreniz dolmuştur. Cevaplarınız otomatik olarak gönderilecektir.");
                    document.getElementById('finish-form').submit(); // Sınavı bitirme formunu gönder
                }
            }

            // Sayaç her saniye güncellenir
            const countdownInterval = setInterval(updateCountdown, 1000);
            updateCountdown(); // Hemen başlat

            // Sayfadan ayrılma/kapatma uyarıları (proctoring için önemli)
            window.addEventListener('beforeunload', function (e) {
                e.preventDefault();
                e.returnValue = '';
            });

            function beforeUnloadHandler(e) {
                e.preventDefault();
                e.returnValue = '';
            }

            window.addEventListener('beforeunload', beforeUnloadHandler);
        });



    </script>
@endsection
