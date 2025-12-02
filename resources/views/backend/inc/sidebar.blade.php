<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">
                <img alt="image" src="{{ asset('otika/assets/img/logo.png') }}" class="header-logo" />
                <span class="logo-name">Otika</span>
            </a>
        </div>

        <ul class="sidebar-menu">

            <li class="menu-header">Genel</li>

            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i data-feather="monitor"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Kurumsal</li>

            <li class="dropdown {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="users"></i><span>Hakkımda</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('admin.team.index') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Ekip Üyeleri</a>
                    </li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('admin.service.*') ? 'active' : '' }}">
                <a class="nav-link" href="#">
                    <i data-feather="check-square"></i><span>Hizmetler</span>
                </a>
            </li>

            <li class="menu-header">Portfolyo</li>

            <li class="dropdown {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i><span>Projeler</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('admin.portfolio.category.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Kategoriler</a>
                    </li>

                    <li class="{{ request()->routeIs('admin.portfolio.index') || request()->routeIs('admin.portfolio.edit') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Tüm Projeler</a>
                    </li>

                    <li class="{{ request()->routeIs('admin.portfolio.create') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Yeni Proje Ekle</a>
                    </li>
                </ul>
            </li>

            <li class="menu-header">Blog & İçerik</li>

            <li class="dropdown {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="file-text"></i><span>Blog İşlemleri</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('admin.blog.category.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Kategoriler</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.blog.tags.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Etiketler (Tags)</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.blog.post.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Yazılar</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.blog.comment.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Yorumlar</a>
                    </li>
                </ul>
            </li>

            <li class="menu-header">Site Bildirimleri</li>

            <li class="{{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                <a class="nav-link" href="#"><i data-feather="mail"></i><span>Gelen Mesajlar</span></a>
            </li>

        </ul>
    </aside>
</div>
