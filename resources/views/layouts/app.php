<nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button aria-expanded="false" class="navbar-toggle collapsed" data-target="#app-navbar-collapse" data-toggle="collapse" type="button">
                        <span class="sr-only">
                            Toggle Navigation
                        </span>
                        <span class="icon-bar">
                        </span>
                        <span class="icon-bar">
                        </span>
                        <span class="icon-bar">
                        </span>
                    </button>
                    <!-- Branding Image -->
                    <router-link :to="{name: 'indexDashboard'}" class="navbar-brand">
                        Toko Dasar
                    </router-link>
                </div>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if (Auth::check())
                        <li>
                            <router-link :to="{name: 'indexDashboard'}">
                                Dashboard
                            </router-link>
                        </li> <li>
                            <router-link :to="{name: 'indexStafToko'}">
                                Staf Toko
                            </router-link>
                        </li>
                        @endif
                        <li class="dropdown">
                            @role('admin')
                            <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                                Master Data
                                <span class="caret">
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <router-link :to="{name: 'indexToko'}">
                                        Toko
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{name: 'indexUser'}">
                                        User Toko
                                    </router-link>
                                </li>
                            </ul>
                            @endif
                        </li>

                        @if (auth()->check())
                        <li class="dropdown">
                            <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                                Produk
                                <span class="caret">
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <router-link :to="{name: 'indexKategoriProduk'}">
                                        Kategori Produk
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{name: 'indexProduk'}">
                                        Produk
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <router-link :to="{name: 'indexKelolaKas'}">
                                Kelola Kas
                            </router-link>
                        </li>


                        <li>
                            <router-link :to="{name: 'indexPelanggan'}">
                                Pelanggan
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{name: 'indexPenjualan'}">
                                Penjualan
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="{name: 'indexLaporan'}">
                                Laporan
                            </router-link>
                        </li>

                        @endif
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                        <li>
                            <a href="{{ route('login') }}">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}">
                                Register
                            </a>
                        </li>
                        @else
                        <li class="dropdown">
                            <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                                {{ Auth::user()->nama_pemilik }}
                                <span class="caret">
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <router-link :to="{name: 'profileTokoIndex'}">
                                        Profile Toko
                                    </router-link>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>

                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
