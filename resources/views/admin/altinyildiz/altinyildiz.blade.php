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
                            <form action="" method="GET" class="mb-3 pt-2">
                                <div class="row">
                                    <div class="col-2">
                                        <label for="Id">Id:</label>
                                        <input type="number" class="form-control" name="id" id="Id" value="{{old('id')}}">
                                    </div>
                                    <div class="col-3">
                                        <label for="Name">Имя товара:</label>
                                        <input type="text" class="form-control" name="name" id="Name" value="{{old('name')}}">
                                    </div>
                                    <div class="col-2">
                                        <label for="Product_code">Код продукта:</label>
                                        <input type="text" class="form-control" name="code" id="Product_code" value="{{old('code')}}">
                                    </div>
                                    <div class="col-3">
                                        <label for="brand">Бренд:</label>
                                        <select class="form-control" name="service_type" id="brand">
                                            <option value="">Все</option>
                                            <option @if(old('service_type') == 1) selected @endif value="1">Altinyildiz Classics</option>
{{--                                            <option @if(old('service_type') == 2) selected @endif value="2"></option>--}}
{{--                                            <option @if(old('service_type') == 3) selected @endif value="3"></option>--}}
{{--                                            <option @if(old('service_type') == 4) selected @endif value="4"></option>--}}
{{--                                            <option @if(old('service_type') == 5) selected @endif value="5"></option>--}}
                                        </select>
                                    </div>
                                    <div class="col-1 d-flex align-items-end">
                                        <a class="btn btn-secondary form-control" href="{{route('admin.products')}}">
{{--                                            Очистить--}}
                                            <i class="fas fa-eraser"></i>
                                        </a>
                                    </div>
                                    <div class="col-1 d-flex align-items-end">
                                        <button type="submit" class="btn-sm btn-primary form-control">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped table-responsive-sm">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Имя товара</th>
                                    <th>Код продукта</th>
                                    <th>Последняя цена</th>
                                    <th>История</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->product_id}}</td>
                                        <td>
                                            <a href="{{ route('admin.product', $product->product_id) }}" class="none-decoration">{{$product->name}}</a>
                                        </td>
                                        <td>{{$product->product_code}}</td>
                                        <td>@isset($product->price->sale_price) {{$product->price->sale_price }} @endisset TL</td>
                                        <td>{{$product->prices->count() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            {{ $products->onEachSide(1)->withQueryString()->links() }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
