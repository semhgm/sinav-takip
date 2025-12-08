@extends('backend.layout.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>'{{ $exam->title }}' Sınavını Öğrencilere Ata</h4>
                </div>
                <div class="card-body">

                    <form action="{{ route('staff.exams.perform-assignment', $exam->id) }}" method="POST">
                        @csrf

                        <h5>Atanacak Öğrenciler</h5>
                        <p class="text-muted">Bu sınava erişim hakkı verilecek öğrencileri seçin.</p>

                        <div class="row">
                            @forelse ($students as $student)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="student_ids[]" value="{{ $student->id }}"
                                               id="student_{{ $student->id }}"
                                            @checked(in_array($student->id, $assignedStudentIds))>
                                        <label class="form-check-label" for="student_{{ $student->id }}">
                                            {{ $student->name }} ({{ $student->email }})
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12"><p class="alert alert-warning">Sistemde atanacak öğrenci bulunamadı.</p></div>
                            @endforelse
                        </div>

                        <button type="submit" class="btn btn-success mt-4">Atamayı Kaydet ve Sınavı Yayınla</button>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
