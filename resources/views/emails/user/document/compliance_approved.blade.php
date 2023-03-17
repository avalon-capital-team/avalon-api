@extends('emails.layouts.default')

@section('content')
<div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
    <table style="vertical-align: top; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: white; padding: 25px 0px 25px 0px  !important;" border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
        <tbody>
            <tr>
                <td class="headerText" style="font-size: 0px; padding: 10px 0  !important; word-break: break-word; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center">
                    <div style="font-family: Helvetica Neue, Helvetica, Arial; font-size: 36px !important; font-weight: 500; line-height: 45px !important; text-align: center; color: #000000;">Olá, {{$userCompliance->user->name}}!</div>
                </td>
            </tr>
            <tr>
                <td class="subheaderText" style="font-size: 0px; padding: 10px 0  !important; word-break: break-word; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center">
                    <div style="font-family: Helvetica Neue, Helvetica, Arial; font-size: 18px !important; font-weight: 400; line-height: 24px !important; text-align: center; color: #8f99a7;padding:0px 75px">
                        Seus <b>documentos</b> foram aprovados.
                        <br><br>
                        <p>Agora você podera disfrutar de toda plataforma, com novos limites.</p>
                        <br><br>
                        Acesse a sua conta agora mesmo.
                    </div>

</div>
</td>
</tr>
<tr>
    <td class="subheaderText" style="font-size: 0px; padding: 10px 0  !important; word-break: break-word; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center">
        <div style="font-family: Helvetica Neue, Helvetica, Arial; font-size: 18px !important; font-weight: 400; line-height: 24px !important; text-align: center; color: #8f99a7;"></div>
    </td>
</tr>
</tbody>
</table>
</div>

@stop
