@extends('layouts.app')

@section('title', 'Вход · Книжная полка')

@section('content')
    <section class="auth-layout">
        <div class="auth-intro">
            <p class="eyebrow">Книжный магазин</p>
            <h1>Вход в систему.</h1>
        </div>
        <form class="form-card" method="POST" action="{{ route('login.store') }}">
            @csrf
            <h2>Войти</h2>
            <label for="email">Электронная почта</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            <label for="password">Пароль</label>
            <input id="password" name="password" type="password" required>
            <button class="button button-primary button-wide" type="submit">Войти в библиотеку</button>
            <div class="demo-accounts">
                <p><strong>Демо-доступ</strong></p>
                <p>Читатель: reader@bookstore.test / reader</p>
                <p>Администратор: admin@bookstore.test / admin</p>
            </div>
        </form>
    </section>
@endsection
