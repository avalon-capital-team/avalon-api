@extends('emails.layouts.default')

@section('content')
<div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
    <table style="vertical-align: top; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: white; padding: 25px 0px 25px 0px  !important;" border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
      <tbody>
        <tr>
          <td class="headerText" style="font-size: 0px; padding: 10px 0  !important; word-break: break-word; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center">
            <div style="font-family: Helvetica Neue, Helvetica, Arial; font-size: 36px !important; font-weight: 500; line-height: 45px !important; text-align: center; color: #000000;">Olá, {{$userCompliance->user->getFirstName()}}!</div>
          </td>
        </tr>
        <tr>
          <td class="subheaderText" style="font-size: 0px; padding: 10px 0  !important; word-break: break-word; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center">
            <div style="font-family: Helvetica Neue, Helvetica, Arial; font-size: 18px !important; font-weight: 400; line-height: 24px !important; text-align: center; color: #8f99a7;padding:0px 75px">
                Seus  <b>documentos</b> foram recusados.
                <br><br>
                @if($userCompliance && $userCompliance->status_id == 3 && $userCompliance->declineReasons->count() > 0)
                    <p><u>Sua última validação retornou os seguintes erros:</u></p>
                    <ul>
                    @foreach($userCompliance->declineReasons as $declineReason)
                    <li>{{$declineReason->declineReasonMessage->description}}</li>
                    @endforeach
                    </ul>
                @endif

                <br><br>
                Envie os seus documentos agora mesmo.
            </div>

            </div>
          </td>
        </tr>
        <tr>
            <td class="ctaButton" style="font-size: 0px; padding: 10px 25px; word-break: break-word; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center" vertical-align="middle">
              <table style="border-collapse: collapse; line-height: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: white; padding: 25px 0px 25px 0px  !important;" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tbody>
                  <tr>
                    <td style="border: none; border-radius: 50px; cursor: auto; mso-padding-alt: 10px 25px; background: #333; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 15px 75px 15px 75px  !important;" align="center" bgcolor="#333" role="presentation" valign="middle">
                      <a style="display: inline-block; background: #333; color: inherit; font-family: Helvetica Neue, Helvetica, Arial; font-size: 18px; font-weight: 500; line-height: 120%; margin: 0; text-decoration: none; text-transform: none; padding: 10px 25px; mso-padding-alt: 0px; border-radius: 50px;" href="{{route('platform.account', 'documentos')}}" target="_blank"> Enviar</a>
                    </td>
                  </tr>
                </tbody>
              </table>
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
