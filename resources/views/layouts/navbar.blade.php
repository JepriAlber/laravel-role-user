<nav class="main-sidebar ps-menu">
    <div class="sidebar-toggle action-toggle">
        <a href="#">
            <i class="fas fa-bars"></i>
        </a>
    </div>
    <div class="sidebar-opener action-toggle">
        <a href="#">
            <i class="ti-angle-right"></i>
        </a>
    </div>
    <div class="sidebar-header">
        <div class="text">AR</div>
        <div class="close-sidebar action-toggle">
            <i class="ti-close"></i>
        </div>
    </div>
    <div class="sidebar-content">
        <ul>
            <li class="{{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="link">
                    <i class="ti-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- @can('read konfigurasi')
            <li class="{{ request()->segment(1) == 'konfigurasi' ? 'active open' : '' }}">
                <a href="#" class="main-menu has-dropdown">
                    <i class="ti-desktop"></i>
                    <span>Konfigurasi</span>
                </a>
                <ul class="sub-menu {{ request()->segment(1) == 'konfigurasi' ? 'expand' : '' }}">
                    @can('read role')
                    <li class="{{ request()->segment(1) == 'konfigurasi' && request()->segment(2) == 'roles' ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}" class="link"><span>Roles</span></a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan --}}
            
                {{-- ambil menu pada dari database menggunakan helper --}}
                @foreach (getMenus() as $menu)
                {{-- melakukan permeriksaan permision user apakah ada permesion yang sama dengan url kalau ada munculkan menunya --}}
                @can('read '.$menu->url)                     
                <li class="{{ request()->segment(1) == $menu->url ? 'active open' : '' }}">
                    <a href="#" class="main-menu has-dropdown">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->name }}</span>
                    </a>
                    <ul class="sub-menu {{ request()->segment(1) == 'konfigurasi' ? 'expand' : '' }}">
                        @foreach ($menu->subMenus as $submenu)
                            @can('read '.$submenu->url)
                                <li class="{{ request()->segment(1) == explode('/',$submenu->url)[0] && request()->segment(2) == explode('/',$submenu->url)[1] ? 'active' : '' }}">
                                    <a href="{{ url($submenu->url) }}" class="link"><span>{{ $submenu->name }}</span></a>
                                </li>
                            @endcan
                        @endforeach
                        
                    </ul>
                </li>
                @endcan
                @endforeach
        </ul>
    </div>
</nav>  