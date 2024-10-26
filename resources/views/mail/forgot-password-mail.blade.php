@component('mail::message')

<h2>Olá, {{ $user->name }}</h2>

<p>Você requisitou a alteração de senha da sua conta {{ config('app.name') }}. Por favor, clique no botão abaixo. </p>

@component('mail::button', ['url' => $resetPasswordLink])
Modificar Senha
@endcomponent


<p>Ou simplesmente copie e cole o link abaixo em seu navegador:</p>
<p>{{ $resetPasswordLink }}</p>
@endcomponent
