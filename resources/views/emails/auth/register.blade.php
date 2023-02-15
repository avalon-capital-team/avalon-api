@extends('emails.layouts.default')
@section('content')

<div class="miro-title-block" style="background-position:center;background-repeat:no-repeat;background-size:100% auto;font-family:Helvetica,Arial,sans-serif;padding:40px 40px 36px">
    <div class="miro-title-block__title font-size-42" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:42px!important;font-stretch:normal;font-style:normal;font-weight:700;letter-spacing:normal;line-height:1.24">Olá {{"{$user->name}"}}</div>
    <div class="miro-title-block__subtitle font-size-20 m-top-16" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:20px!important;font-stretch:normal;font-style:normal;font-weight:400;letter-spacing:normal;line-height:1.4;margin-top:16px;opacity:.6">Agora você faz parte da Avalon Capital, acesse agora a sua conta em nosso aplicativo e finalize o seu cadastro.</div>
</div>

<!-- <table class="row" style="border-collapse:collapse;border-spacing:0;font-family:Helvetica,Arial,sans-serif;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
    <tbody style="font-family:Helvetica,Arial,sans-serif">
        <tr style="font-family:Helvetica,Arial,sans-serif;padding:0;text-align:left;vertical-align:top">
            <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.43;margin:0 auto;padding:0;padding-bottom:0;padding-left:40px;padding-right:40px;text-align:left;width:440px">
                <table style="border-collapse:collapse;border-spacing:0;font-family:Helvetica,Arial,sans-serif;padding:0;text-align:left;vertical-align:top;width:100%">
                    <tr style="font-family:Helvetica,Arial,sans-serif;padding:0;text-align:left;vertical-align:top">
                        <th style="Margin:0;color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.43;margin:0;padding:0;text-align:left">
                            <a href="{{config('app.url_frontend')}}/login" class="btn layout-3__btn" target="_blank" style="Margin:0;background:#c3c3c3;border-radius:4px;color:#fff;display:inline-block;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:700;line-height:1.43;margin:0;outline:0;padding:20px 21px;text-align:center;text-decoration:none;width:238px">Acessar meu perfil</a>
                        </th>
                        <th class="expander" style="Margin:0;color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;line-height:1.43;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                    </tr>
                </table>
            </th>
        </tr>
    </tbody>
</table> -->
<div class="miro-title-block" style="background-position:center;background-repeat:no-repeat;background-size:100% auto;font-family:Helvetica,Arial,sans-serif;padding:10px 0 23px 40px">
    <div class="miro-title-block__title font-size-42" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:42px!important;font-stretch:normal;font-style:normal;font-weight:700;letter-spacing:normal;line-height:1.24"></div>
    <div class="miro-title-block__subtitle font-size-20 m-top-16" style="color:#23262F;font-family:Helvetica,Arial,sans-serif;font-size:20px!important;font-stretch:normal;font-style:normal;font-weight:400;letter-spacing:normal;line-height:1.4;margin-top:16px;opacity:.6">Obrigado por fazer parte da Avalon Capital.</div>
</div>

@endsection
