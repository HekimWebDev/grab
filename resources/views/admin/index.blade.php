@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.sidebar')

    <!-- Main Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                </div>
                @if (session('error'))
                    <div class="alert alert-default-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-default-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors)
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-default-danger">{{ $error }}</div>
                    @endforeach
                @endif
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- /.col -->
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#settings" data-toggle="tab">Изменить
                                            пароль</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#profile" data-toggle="tab">Настройки профиля</a>
                                    </li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="settings">
                                        <div class="card-header col-md-8">Изменить пароль</div>
                                        <div class="card-body register-card-body col-md-8">
                                            <form class="form-horizontal" method="post" id="my-form"
                                                  action="{{ route('changePassword') }}">
                                                @csrf
                                                <div
                                                    class="input-group mb-3 {{ $errors->has('current-password') ? ' has-error' : '' }}">
                                                    <input name="current-password" type="password"
                                                           class="form-control @error('current-password') is-invalid @enderror"
                                                           id="current-password" placeholder="Текущий пароль">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-lock"></span>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('current-password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('current-password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div
                                                    class="input-group mb-3 {{ $errors->has('new-password') ? ' has-error' : '' }}">
                                                    <input name="new-password" type="password"
                                                           class="form-control @error('new-password') is-invalid @enderror"
                                                           id="password1" placeholder="Новый пароль">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-lock"></span>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('new-password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('new-password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="input-group mb-3">
                                                    <input id="new-password-confirm" type="password"
                                                           placeholder="Подтвердить пароль"
                                                           class="form-control" name="new-password_confirmation"
                                                           required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-lock"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mb-3" style="margin-left: 2px">
                                                    <label>
                                                        <input type="checkbox" class="password-checkbox"> Показать
                                                        пароль
                                                    </label>
                                                </div>

                                                <button type="button" class="btn btn-danger"
                                                        onclick="resetForm();">Отменить
                                                </button>
                                                <button type="submit" class="btn btn-primary">Изменить пароль</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="profile">
                                        <div class="card-header col-md-8">Настройки профиля</div>
                                        <div class="card-body register-card-body col-md-8">

                                            <form class="form-horizontal" method="post"
                                                  action="{{--{{ route('user-profile-information.update') }}--}}">
                                                @csrf
                                                @method('PUT')
                                                <div class="input-group mb-3">
                                                    <input type="text"
                                                           class="form-control @error('name') is-invalid @enderror"
                                                           name="name" placeholder="Name"
                                                           value="{{ old('name') ?? $user->name }}">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-user"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mb-3">
                                                    <input type="email" name="email"
                                                           class="form-control @error('email') is-invalid @enderror"
                                                           placeholder="Email"
                                                           value="{{ old('email') ?? $user->email }}">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-envelope"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Изменить</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
