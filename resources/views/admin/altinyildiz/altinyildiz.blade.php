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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Фильтр</h2>
                            <form action="" method="GET" class="mb-3">
                                <div class="row">
                                    <div class="col-2">
                                        <label for="Id">Id:</label>
                                        <input type="number" class="form-control" name="id" id="Id">
                                    </div>
                                    <div class="col-4">
                                        <label for="Name">Имя товара:</label>
                                        <input type="text" class="form-control" name="name" id="Name">
                                    </div>
                                    <div class="col-3">
                                        <label for="Product_code">Код продукта:</label>
                                        <input type="text" class="form-control" name="code" id="Product_code">
                                    </div>
                                    <div class="col d-flex align-items-end">
                                        <button type="submit" class="btn btn-secondary form-control">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="col-2 d-flex align-items-end">
                                        <a class="btn btn-primary form-control" href="{{route('admin.a-y')}}">Очистить</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Имя товара</th>
                                    <th>Код продукта</th>
                                    <th>Цены</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->product_id}}</td>
                                        <td>{{$product->name}}</td>
                                        <td>{{$product->product_code}}</td>
                                        <td>
                                            <a href="{{route('admin.a-y-single', $product->product_id)}}"
                                               class="btn btn-outline-primary position-relative">
                                               @isset($product->price->sale_price)
                                                {{$product->price->sale_price }}
                                                @endisset
                                                @if($product->old_prices != 0)
                                                    <span
                                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                        {{$product->old_prices}}
                                                    </span>
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="ml-4">
                            {{ $products->withQueryString()->links() }}
                        </div>
                        <!-- /.card-body -->
                    </div>

                    <!-- /.card -->
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
