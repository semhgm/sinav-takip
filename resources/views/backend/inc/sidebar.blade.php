<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route(auth()->user()->role . '.dashboard') }}">
                <img alt="image" src="{{ asset('otika/assets/img/logo.png') }}" class="header-logo" />
                <span class="logo-name">Sınav Takip</span>
            </a>
        </div>

        <ul class="sidebar-menu">

            {{-- ********** GENEL DASHBOARD ********** --}}
            <li class="menu-header">Genel</li>

            <li class="{{ request()->routeIs(auth()->user()->role . '.dashboard') ? 'active' : '' }}">
                {{-- Herkes kendi rolünün dashboard'una yönlendirilecek --}}
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="nav-link">
                    <i data-feather="monitor"></i><span>Dashboard</span>
                </a>
            </li>

            {{-- ********** ADMIN MENÜSÜ ********** --}}
            @if(auth()->user()->role === 'admin')
                <li class="menu-header">Yönetim ve Tanımlamalar</li>

                {{-- Sınav İşlemleri --}}
                <li class="dropdown {{ request()->routeIs('admin.exams.*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="clipboard"></i><span>Sınavlar</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('admin.exams.index') ? 'active' : '' }}">
                            <a class="nav-link" href="#">Tüm Sınavlar</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.exams.create') ? 'active' : '' }}">
                            <a class="nav-link" href="#">Yeni Sınav Ekle</a>
                        </li>
                    </ul>
                </li>

                {{-- Soru Yönetimi --}}
                <li class="dropdown {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="help-circle"></i><span>Sorular</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('admin.questions.index') ? 'active' : '' }}">
                            <a class="nav-link" href="#">Tüm Sorular</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.questions.categories.*') ? 'active' : '' }}">
                            <a class="nav-link" href="#">Kategoriler</a>
                        </li>
                    </ul>
                </li>

                {{-- Kullanıcı ve Rol Yönetimi --}}
                <li class="dropdown {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="users"></i><span>Kullanıcılar</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <a class="nav-link" href="#">Tüm Kullanıcılar</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.users.assign.staff') ? 'active' : '' }}">
                            <a class="nav-link" href="#">Gözetmen/Staff Atama</a>
                        </li>
                    </ul>
                </li>

            @endif


            {{-- ********** STAFF (GÖZETMEN/ÖĞRETMEN) MENÜSÜ ********** --}}
            @if(auth()->user()->role === 'staff')
                <li class="menu-header">Sınav Takip ve Gözetim</li>

                {{-- Sınav İşlemleri (Önce Admin'deydi, şimdi Staff'e geldi) --}}
                {{-- Aktiflik kontrolü artık 'staff.exams.*' rotaları üzerinden yapılır --}}
                <li class="dropdown {{ request()->routeIs('staff.exams.*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="clipboard"></i><span>Sınavlar</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('staff.exams.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('staff.exams.index') }}">Tüm Sınavlar</a>
                        </li>
                        <li class="{{ request()->routeIs('staff.exams.create') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('staff.exams.create') }}">Yeni Sınav Oluştur</a>
                        </li>
                        <li class="{{ request()->routeIs('staff.questions.categories.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('staff.categories.index')}}">Kategoriler</a>
                        </li>
                    </ul>
                </li>
                {{-- Canlı Takip --}}
                <li class="{{ request()->routeIs('staff.live.monitor') ? 'active' : '' }}">
                    <a class="nav-link" href="#">
                        <i data-feather="video"></i><span>Canlı Sınav Takibi</span>
                    </a>
                </li>

                {{-- İhlal Raporları --}}
                <li class="{{ request()->routeIs('staff.violations.index') ? 'active' : '' }}">
                    <a class="nav-link" href="#">
                        <i data-feather="alert-triangle"></i><span>İhlal Raporları</span>
                    </a>
                </li>

                {{-- Manuel Puanlama --}}
                <li class="{{ request()->routeIs('staff.grading.index') ? 'active' : '' }}">
                    <a class="nav-link" href="#">
                        <i data-feather="edit-2"></i><span>Manuel Puanlama</span>
                    </a>
                </li>
            @endif


            {{-- ********** STUDENT (ÖĞRENCİ) MENÜSÜ ********** --}}
            @if(auth()->user()->role === 'student')
                <li class="menu-header">Sınavlar ve Sonuçlar</li>

                {{-- Mevcut Sınavlar --}}
                <li class="{{ request()->routeIs('student.exams.available') ? 'active' : '' }}">
                    <a class="nav-link" href="#">
                        <i data-feather="cast"></i><span>Mevcut Sınavlar</span>
                    </a>
                </li>

                {{-- Sınav Geçmişi --}}
                <li class="{{ request()->routeIs('student.exams.history') ? 'active' : '' }}">
                    <a class="nav-link" href="#">
                        <i data-feather="book-open"></i><span>Sınav Geçmişi</span>
                    </a>
                </li>

                {{-- Sonuç ve Analiz --}}
                <li class="{{ request()->routeIs('student.results.index') ? 'active' : '' }}">
                    <a class="nav-link" href="#">
                        <i data-feather="bar-chart-2"></i><span>Sonuçlarım</span>
                    </a>
                </li>
            @endif

            {{-- ********** ÇIKIŞ İŞLEMİ (HERKES İÇİN ORTAK) ********** --}}
            <li class="menu-header">Oturum</li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="nav-link text-danger">
                        <i data-feather="log-out"></i>
                        <span>Çıkış Yap</span>
                    </a>
                </form>
            </li>

        </ul>
    </aside>
</div>
