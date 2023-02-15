@extends('emails.layouts.default')
@section('content')

<div class="miro-title-block" style="background-position:center;background-repeat:no-repeat;background-size:100% auto;font-family:Helvetica,Arial,sans-serif;padding:40px 40px 36px">
    <div class="miro-title-block__title font-size-42" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:42px!important;font-stretch:normal;font-style:normal;font-weight:700;letter-spacing:normal;line-height:1.24">Código de Autenticação</div>
    <div class="miro-title-block__subtitle font-size-20 m-top-16" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:20px!important;font-stretch:normal;font-style:normal;font-weight:400;letter-spacing:normal;line-height:1.4;margin-top:16px;opacity:.6">Olá {{"{$user->name}"}}</div>
</div>
<div class="miro-confirmation-code-block" style="font-family:Helvetica,Arial,sans-serif;padding:0 40px">
    <div class="miro-title-block__subtitle font-size-20 m-top-16" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:20px!important;font-stretch:normal;font-style:normal;font-weight:400;letter-spacing:normal;line-height:1.4;margin-top:16px;opacity:.6">Este é o seu código de autenticação.</div>
    <div class="miro-confirmation-code-block__code" style="background-color:#f3f4f8;border-radius:4px;color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:48px;font-stretch:normal;font-style:normal;font-weight:700;height:128px;letter-spacing:normal;line-height:128px;text-align:center">{{$code}}</div>
</div>
<div class="miro-title-block" style="background-position:center;background-repeat:no-repeat;background-size:100% auto;font-family:Helvetica,Arial,sans-serif;padding:10px 0 23px 40px">
    <div class="miro-title-block__subtitle font-size-20 m-top-16" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:25px!important;font-stretch:normal;font-style:normal;font-weight:500;letter-spacing:normal;line-height:1.4;margin-top:16px;opacity:.6">Insira-o no App para continuar aproveitando a Avalon Capital.</div>
    <div class="miro-title-block__subtitle font-size-20 m-top-16" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:20px!important;font-stretch:normal;font-style:normal;font-weight:400;letter-spacing:normal;line-height:1.4;margin-top:16px;opacity:.6">Obrigada,</div>
    <div class="miro-title-block__subtitle font-size-20 m-top-16" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:20px!important;font-stretch:normal;font-style:normal;font-weight:400;letter-spacing:normal;line-height:1.4;margin-top:16px;opacity:.6">Equipe Avalon Capital.</div>
</div>

@endsection
