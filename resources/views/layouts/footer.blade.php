<footer class="footer-section bg-dark on-dark">
    <div class="container">
        <div class="section-space-sm">
            <div class="row">
                <div class="col-lg-3 col-md-9 me-auto">
                    <div class="footer-item mb-5 mb-lg-0">
                        <a href="index.html" class="footer-logo-link logo-link">
                            <img class="logo-dark logo-img" src="/images/logo-black.svg" alt="logo">
                            <img class="logo-light logo-img" src="/images/logo-white.svg" alt="logo">
                        </a>
                        <p class="my-4 footer-para">O primeiro e maior mercado digital do mundo para negociar tokens.</p>
                        <ul class="styled-icon">
                            <li><a href="#"><em class="icon ni ni-twitter"></em></a></li>
                            <li><a href="#"><em class="icon ni ni-facebook-f"></em></a></li>
                            <li><a href="#"><em class="icon ni ni-instagram"></em></a></li>
                            {{-- <li><a href="#"><em class="icon ni ni-pinterest"></em></a></li> --}}
                        </ul>
                    </div><!-- end footer-item -->
                </div><!-- end col-lg-3 -->
                <div class="col-lg-8">
                    <div class="row g-gs">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="footer-item">
                                <h5 class="mb-4">Tokens</h5>
                                <ul class="list-item list-item-s1">
                                    <li><a href="{{route('platform.marketplace.tokens')}}">Ver todos os tokens</a></li>
                                    <li><a href="{{route('platform.marketplace.tokens')}}">Tokenize</a></li>
                                </ul>
                            </div><!-- end footer-item -->
                        </div><!-- end col -->
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="footer-item">
                                <h5 class="mb-4">Conta</h5>
                                <ul class="list-item list-item-s1">
                                    @if(Auth::user())
                                    <li><a class="" href="{{route('platform.account')}}">Perfil</a></li>
                                    <li class=""><a class="" href="{{route('platform.account','endereco')}}">Endereço</a></li>
                                    <li class=""><a class="" href="{{route('platform.account','seguranca')}}">Segurança</a></li>
                                    <li class=""><a class="" href="{{route('platform.account','autenticacao-em-dois-fatores')}}">2FA</a></li>
                                    <li class=""><a class="" href="{{route('platform.account','documentos')}}">Documentos</a></li>
                                    <li>
                                        @else
                                    <li class=""><a class="" href="{{route('login')}}">Acessar minha conta</a></li>
                                    <li class=""><a class="" href="{{route('register')}}">Criar uma conta</a></li>
                                    <li class=""><a class="" href="{{route('password.request')}}">Esqueci minha senha</a></li>
                                    @endif
                                </ul>
                            </div><!-- end footer-item -->
                        </div><!-- end col-lg-3 -->
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="footer-item">
                                <h5 class="mb-4">Empresa</h5>
                                <ul class="list-item list-item-s1">
                                    <li><a href="#">Sobre</a></li>
                                    <li><a href="{{route('platform.faq')}}">FAQ</a></li>
                                    <li><a href="#">Contato</a></li>
                                </ul>
                            </div><!-- end footer-item -->
                        </div><!-- end col-lg-3 -->
                    </div>
                </div>
            </div><!-- end row -->
        </div><!-- end section-space-sm -->
        <hr class="bg-white-slim my-0">
        <div class="copyright-wrap d-flex flex-wrap py-3 align-items-center justify-content-between">
            <p class="footer-copy-text py-2">Split Assets &copy; {{date('Y')}}. Todos os direitos reservados. <a href="https://splitassets.com.br/pdf/termos_de_uso.pdf" target="_blank">Termos de uso </a></p>
            <ul class="list-item list-item-s1 list-item-inline">
                <li><a href="#">Compre Tokens</a></li>
                <li><a href="#">Emitir meu token</a></li>
            </ul>
        </div><!-- end d-flex -->
    </div><!-- .container -->
</footer><!-- end footer-section -->