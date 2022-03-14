@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header ml-2 mr-2">
            <h1>Dashboard</h1>
        </section>

        <!-- Main content -->
        <section class="content ml-2 mr-2">
            <div class="row">
                <div class="col-md" style="height: 120px;">
                    <div class="w-100 h-100 p-2" style="background-color: #17a2b8; border-radius: 3px">
                        <h3 style="font-weight: bold; color: #fff">{{$counts[1]}}</h3>
                        <div class="d-flex align-items-end h-100">
                            <div style="color: red;">Altinyildiz Classik</div>
                        </div>
                    </div>
                </div>
                <div class="col-md"></div>
                <div class="col-md"></div>
                <div class="col-md"></div>
                <div class="col-md"></div>
            </div>
        </section>
    </div>

@endsection
