## Avalon Capital

Backend Project (API)

The Avalon Capital project consists of a social network.

- [API Documentation](https://documenter.getpostman.com/view/9111037/2s93CHuaK1)

## Features

- [x] Auth
  - [x] Login
  - [x] Password Recover
  - [x] Create new Password
  - [x] Register
- [x] Onboarding
  - [x] Ongs
  - [x] Dados Pessoais
  - [x] Documentos
- [x] Settings
  - [x] General
  - [x] Notifications
  - [x] Profile
  - [x] Compliance
  - [x] Privacy
  - [x] Password
  - [x] Access logs
- [x] Aporte
  - [x] Criar Aporte
  - [x] Novo Aporte
- [x] Wallet
  - [x] Listagem de crypto moedas
  - [x] Conversao de ativos
- [x] Gestores
  - [x] Lista de seus clientes
- [x] Assessores
  - [x] Lista de seus clientes

## Admin API

- [x] Auth
  - [x] Login
  - [x] Password Recover
  - [x] Create new Password
- [x] Dashboard
  - [x] Total dos clientes
  - [x] Total dos gestores
  - [x] Total dos assessores
  - [x] Valor total aportado
  - [x] Valor total aportado reinvestido
  - [x] Valor total aportado nao reinvestido
  - [x] Valor total rendido
  - [x] Valor total rendido reinvestido
  - [x] Valor total rendido nao reinvestido
  - [x] Valor total de saque
  - [x] Valor total de deposito
  - [x] Lista de planos ativos
- [] Usuários
  - [x] Lista dos usuários (filtro "clientes, gestores, assessores")
  - [] Usuário
    - [x] Dados do usuário
    - [x] Dados do compliance
    - [x] Dados do endereço
    - [x] Dados do 2FA
    - [x] Dados dos aportes
    - [x] Dados da carteira
    - [x] Mudar tipo do usuário
    - [x] Aprovar/rejeitar usuário
    - [x] Deletar usuário
    - [x] Setar um gestor ou assessor
    - [] Criar aporte
    - [] Aprovar/rejeitar aporte
    - [x] Ativar/desativar reaporte
    - [] Reaportar
    - [] Deletar aporte
- [] Saques
  - [x] Lista de saques (filtro "pendente, aprovado, rejeitado")
  - [] Deletar saque
- [] Depósitos
  - [x] Lista de depósitos (filtro "pendente, aprovado, rejeitado")
  - [] Deletar depósito
- [] Extrato
  - [x] Lista de extrato (filtro "moeda, tipo, usuario")
- [] Planos
  - [x] Lista dos planos
  - [] Deletar plano
- [] Porcentagem
  - [x] Lista das porcentagens
  - [] Deletar porcentagem
- [] Moedas
  - [x] Lista das moedas
  - [] Deletar moeda

## Library

- Framework: **Laravel 9.X**
  - [pragmarx/google2fa-laravel](https://github.com/antonioribeiro/google2fa-laravel)
  - [rappasoft/laravel-authentication-log](https://github.com/rappasoft/laravel-authentication-log)
  - [torann/geoip](https://github.com/Torann/laravel-geoip)
  - [fruitcake/laravel-cors](https://github.com/fruitcake/laravel-cors)
  - [bacon/bacon-qr-code](https://github.com/bacon/bacon-qr-code)
  - [guzzlehttp/guzzle](https://github.com/guzzlehttp/guzzle)
  - [laravel-notification-channels/onesignal](https://github.com/laravel-notification-channels/onesignal)
  - [league/flysystem-aws-s3-v3](https://github.com/league/flysystem-aws-s3-v3)
  - [pusher/pusher-php-server](https://github.com/pusher/pusher-php-server)
  - [ramsey/uuid](https://github.com/ramsey/uuid)
