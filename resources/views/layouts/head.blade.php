<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="author" content="Sortnio">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,  initial-scale=1.0">
<meta name="description" content="Avalon Capital - Tokenizar ativos nunca foi tão fácil">
<meta name="keywords" content="token,tokenização,crypto,eth,btc">

<meta property="og:url" content="{{route('platform.marketplace.home')}}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Avalon Capital" />
<meta property="og:description" content="Tokenizar ativos nunca foi tão fácil" />
<meta property="og:image" content="/images/logo-black.svg" />


<title> {{{$title}}} @section('title')@show</title>

<link rel="icon" sizes="16x16" href="/images/favicon.png">

<link rel="stylesheet" href="{{ mix('css/all.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">
</script>

@routes

@livewireStyles

@section('scripts_default')
<script src="{{ mix('js/all.js') }}"></script>
@livewireScripts
@livewireChartsScripts
@include('partials.toast')
@stop
