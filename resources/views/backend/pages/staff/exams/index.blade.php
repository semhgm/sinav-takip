// resources/views/backend/pages/staff/exams/index.blade.php örneği

@extends('backend.layout.app') // Ana template'inizi çağırın

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
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
                                {{-- Buraya sınav tablosu gelecek --}}
                                <p>Burada tüm oluşturulan sınavlar listelenecek.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
