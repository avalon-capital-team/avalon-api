<header class="header-section has-header-main {{(Route::is('platform.account') || Route::is('platform.account.*') || Route::is('platform.sales') || Route::is('platform.sales.*') || Route::is('platform.wallet') || Route::is('platform.wallet.*')) ? '' : 'bg-gradient'}}">
    <div class="header-main is-sticky {{(Route::is('platform.account') || Route::is('platform.account.*') || Route::is('platform.sales') || Route::is('platform.sales.*') || Route::is('platform.wallet') || Route::is('platform.wallet.*')) ? '' : 'is-transparent'}}">
        <div class="container">
            <div class="header-wrap">
                <div class="header-logo">
                    <a href="/" class="logo-link">
                        <img class="logo-dark logo-img logoSize" src="/images/logo-black.svg" alt="logo">
                        <img class="logo-light logo-img logoSize" src="/images/logo-white.svg" alt="logo">
                    </a>
                </div>
                <div class="header-mobile-action">
                    @if(Auth::user())
                    <div class="header-mobile-wallet me-2">
                        <a class="icon-btn" href="{{route('platform.account')}}">
                            <em class="ni ni-user"></em>
                        </a>
                    </div>
                    <div class="header-mobile-wallet me-2">
                        <a class="icon-btn" href="{{route('platform.wallet')}}">
                            <em class="ni ni-wallet"></em>
                        </a>
                    </div>
                    @else
                    <div class="header-mobile-wallet me-2">
                        <a class="text-primary" href="{{route('register')}}">
                            <u>Criar conta</u>
                        </a>
                    </div>

                    <div class="header-mobile-wallet me-2">
                        <a class="btn btn-primary btn-sm" href="{{route('login')}}">
                            Entrar
                        </a>
                    </div>
                    @endif

                    <div class="header-toggle">
                        <button class="menu-toggler">
                            <em class="menu-on menu-icon ni ni-menu"></em>
                            <em class="menu-off menu-icon ni ni-cross"></em>
                        </button>
                    </div>
                </div>

                <nav class="header-menu menu nav">
                    <ul class="menu-list ms-lg-auto">
                        <li class="menu-item">
                            <a href="{{route('platform.marketplace.home')}}" class="menu-link">Início</a>
                        </li>
                        @if(Auth::user())
                        <li class="menu-item has-sub d-block d-sm-none">
                            <a href="#" class="menu-link menu-toggle">Conta</a>
                            <div class="menu-sub">
                                <ul class="menu-list">
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.account')}}"><em class="ni ni-user me-2"></em> Perfil</a></li>
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.account','endereco')}}"><i class='bx bxs-map-pin me-2'></i> Endereço</a></li>
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.account','seguranca')}}"><i class='bx bx-key me-2' ></i>Segurança</a></li>
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.account','autenticacao-em-dois-fatores')}}"><i class='bx bx-dialpad-alt me-2'></i>2FA</a></li>
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.account','financeiro')}}"><i class='bx bxs-bank me-2'></i>Financeiro</a></li>
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.account','documentos')}}"><i class='bx bx-file me-2'></i>Documentos</a></li>
                                    @if(session('check_as_from'))
                                    <li><a class="dropdown-item card-generic-item" href="{{route('platform.account.backTo')}}"><em class="ni ni-arrow-left me-2"></em> Voltar p/ admin</a></li>
                                    @endif
                                    <li><a class="dropdown-item card-generic-item" href="{{route('logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><em class="ni ni-power me-2"></em> Desconectar</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="menu-item has-sub d-block d-sm-none">
                            <a href="#" class="menu-link menu-toggle">Carteira</a>
                            <div class="menu-sub">
                                <ul class="menu-list">
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.wallet')}}"><em class="ni ni-wallet me-2"></em> Ir para carteira </a></li>
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.wallet.extract')}}"><i class='bx bx-candles me-2'></i>Extrato de operações </a></li>
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.wallet.deposit.fiat')}}"><i class='bx bxs-down-arrow-square me-2' ></i> Depósitos</a></li>
                                    <li class="menu-item"><a class="dropdown-item card-generic-item" href="{{route('platform.wallet.withdrawal.fiat')}}"><i class='bx bxs-up-arrow-square me-2' ></i> Saques</a></li>
                                </ul>
                            </div>
                        </li>


                        @endif
                        @if(Auth::user() && Auth::user()->type == 'seller')
                        <li class="menu-item has-sub">
                            <a href="#" class="menu-link menu-toggle">Vendas</a>
                            <div class="menu-sub">
                                <ul class="menu-list">
                                    <li class="menu-item"><a href="{{route('platform.sales')}}" class="menu-link">Minhas vendas</a></li>
                                    <li class="menu-item"><a href="{{route('platform.sales.extract')}}" class="menu-link">Extrato completo</a></li>
                                    <li class="menu-item"><a href="{{route('platform.sales.token_sale.create')}}" class="menu-link">Cadastrar novo Token</a></li>
                                </ul>
                            </div>
                        </li>
                        @endif
                        <li class="menu-item">
                            <a href="{{route('platform.marketplace.tokens')}}" class="menu-link">Tokens</a>
                        </li>

                         <li class="menu-item">
                            <a href="{{route('platform.marketplace.information.tokenize')}}" class="menu-link">Tokenize</a>
                        </li>

                        <li class="menu-item has-sub">
                            <a href="#" class="menu-link menu-toggle">Aprenda</a>
                            <div class="menu-sub">
                                <ul class="menu-list">
                                    <li class="menu-item"><a href="{{route('platform.faq')}}" class="menu-link">FAQ</a></li>
                                    <li class="menu-item"><a href="#" class="menu-link">Como emitir tokens?</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                        @if(Auth::user())
                        <ul class="menu-btns menu-btns-2">
                            <li class="d-none d-lg-inline-block dropdown">
                                <button type="button" class="icon-btn icon-btn-s1 theme-toggler1" title="Toggle Dark/Light mode">
                                    <span>
                                        <em class="ni ni-moon icon theme-toggler-show" style="color: #333!important"></em>
                                        <em class="ni ni-sun icon theme-toggler-hide" style=""></em>
                                    </span>

                                </button>
                            </li>
                            <li class="d-none d-lg-inline-block dropdown">
                                <button type="button" class="icon-btn icon-btn-s1" data-bs-toggle="dropdown"><em class="ni ni-wallet"></em></button>
                                <ul class="dropdown-menu card-generic card-generic-s3 dropdown-menu-end mt-2">
                                    <li><a class="dropdown-item card-generic-item" href="{{route('platform.wallet')}}"><em class="ni ni-wallet me-2"></em> Ir para carteira </a></li>
                                    <li><a class="dropdown-item card-generic-item" href="{{route('platform.wallet.extract')}}"><i class='bx bx-candles me-2'></i>Extrato de operações </a></li>
                                    <li><a class="dropdown-item card-generic-item" href="{{route('platform.wallet.deposit.fiat')}}"><i class='bx bxs-down-arrow-square me-2' ></i> Depósitos</a></li>
                                    <li><a class="dropdown-item card-generic-item" href="{{route('platform.wallet.withdrawal.fiat')}}"><i class='bx bxs-up-arrow-square me-2' ></i> Saques</a></li>
                                </ul>
                            </li>

                            <li class="d-none d-lg-inline-block dropdown">
                                <button type="button" class="icon-btn icon-btn-s1" data-bs-toggle="dropdown"><em class="ni ni-user"></em></button>
                                <ul class="dropdown-menu card-generic card-generic-s3 dropdown-menu-end mt-2">
                                    <li>
                                        <h6 class="dropdown-header">{{Auth::user()->name}}</h6>
                                    </li>
                                    <li><a class="dropdown-item card-generic-item" href="{{route('platform.account')}}"><em class="ni ni-user me-2"></em> Perfil</a></li>
                                    <li class=""><a class="dropdown-item card-generic-item" href="{{route('platform.account','endereco')}}"><i class='bx bxs-map-pin me-2'></i> Endereço</a></li>
                                    <li class=""><a class="dropdown-item card-generic-item" href="{{route('platform.account','seguranca')}}"><i class='bx bx-key me-2' ></i>Segurança</a></li>
                                    <li class=""><a class="dropdown-item card-generic-item" href="{{route('platform.account','autenticacao-em-dois-fatores')}}"><i class='bx bx-dialpad-alt me-2'></i>2FA</a></li>
                                    <li class=""><a class="dropdown-item card-generic-item" href="{{route('platform.account','financeiro')}}"><i class='bx bxs-bank me-2'></i>Financeiro</a></li>
                                    <li class=""><a class="dropdown-item card-generic-item" href="{{route('platform.account','documentos')}}"><i class='bx bx-file me-2'></i>Documentos</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    @if(session('check_as_from'))
                                    <li><a class="dropdown-item card-generic-item" href="{{route('platform.account.backTo')}}"><em class="ni ni-arrow-left me-2"></em> Voltar p/ admin</a></li>
                                    @endif
                                    <li><a class="dropdown-item card-generic-item" href="{{route('logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><em class="ni ni-power me-2"></em> Desconectar</a></li>
                                </ul>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        @else
                        <ul class="menu-btns">
                            <li><a href="{{route('login')}}" class="btn btn-primary">Entrar <i class="bx bx-hot"></i></a></li>
                            <li>
                                <a href="#" class="theme-toggler" title="Toggle Dark/Light mode">
                                    <span>
                                   <em class="ni ni-moon icon theme-toggler-show" style="color: #333!important"></em>
                                        <em class="ni ni-sun icon theme-toggler-hide" style="color: #333!important"></em>
                                    </span>
                                    <span class="theme-toggler-text">Dark Mode</span>
                                </a>
                            </li>
                        </ul>
                        @endif
                    </ul>
                </nav>
                <div class="header-overlay"></div>
            </div>
        </div>
    </div>

    @if(Route::is('platform.marketplace.home'))
        @include('platform.marketplace.home.components.slider')
    @elseif (Route::is('platform.marketplace.*'))
        @if(!Route::is('platform.marketplace.tokens.show'))
        <div class="hero-wrap sub-header">
            <div class="container">
                <div class="hero-content text-center py-0">
                    <h1 class="hero-title">{{$title}}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-s1 justify-content-center mt-3 mb-0">
                            <li class="breadcrumb-item"><a href="{{route('platform.marketplace.home')}}">{{env('APP_NAME')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        @endif
    @elseif (Route::is('platform.account') || Route::is('platform.account.*') || Route::is('platform.sales') || Route::is('platform.sales.*')|| Route::is('platform.wallet*'))
    <div class="hero-wrap sub-header" style="background-image: url('/assets/images/bgs/bg001.png');background-size: 100% auto;">
        <div class="overlay"></div>
        <div class="container">
            <div class="hero-content py-0 d-flex align-items-center">
                <div class="avatar avatar-3 flex-shrink-0">
                    <img src="{{Auth::user()->avatar()}}" alt="avatar">
                </div><!-- end avatar -->
                <div class="author-hero-content-wrap d-flex flex-wrap justify-content-between ms-3 flex-grow-1">
                    <div class="author-hero-content me-3">
                        <h4 class="hero-author-title mb-1 text-white">{{Auth::user()->name}}</h4>
                        <p class="hero-author-username mb-1 text-white">{{Auth::user()->email}}</p>
                    </div><!-- end author-hero-content -->
                    <div class="hero-action-wrap d-flex align-items-center my-2">
                        <button class="btn btn-light" disabled>{{$title}}</button>
                    </div>
                </div><!-- end author-hero-content-wrap -->
            </div><!-- hero-content -->
        </div>
    </div>
    @endif

</header><!-- end header-section -->
