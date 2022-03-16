@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">



        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{--                            <li class="breadcrumb-item"><a href="#">Home</a></li>--}}
                            {{--                            <li class="breadcrumb-item active">Blank Page</li>--}}
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            @include('admin.partials.message')

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <i class="fas fa-globe"> {{ $product->getMagazine() }}</i>
                                    </h4>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-6 invoice-col">
                                    <address>
                                        <strong>Название продукта:</strong><br>
                                        {{ $product->name }}<br>
                                        <strong>Последняя проверка: </strong>
                                        {{ $product->updated_at->diffForHumans() }}
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6 invoice-col">
                                    <b>Product ID: </b>{{$product->product_id}}<br>
                                    <strong>Код продукта: </strong>{{$product->product_code}}<br>
                                    <strong>URL продукта: </strong><br>
                                    <a href="{{ 'https://www.altinyildizclassics.com'.$product->product_url }}" target="_blank">{{$product->product_url}}</a>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row mt-5">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Имя товара</th>
                                            <th>Код продукта</th>
                                            <th>Основная цена</th>
                                            <th>Продажная цена</th>
                                            <th>Дата</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($product->prices as $prod)
                                            <tr>
                                                <td>{{ $prod->product_id }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->product_code }}</td>
                                                <td>{{ $prod->original_price }} TL</td>
                                                <td>{{ $prod->sale_price }} TL</td>
                                                <td>{{ $prod->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-12">
                                    <div class="float-right">
                                        <form action="{{route('check-price', [$product->product_id, $product->service_type])}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success"><i
                                                    class="fa fa-redo"></i> Проверка цен
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
