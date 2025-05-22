@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit order</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('order.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('order.update',$order->id) }}" method="POST">
        @csrf

        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>customer:</strong>
                    <input type="text" name="customer" value="{{ $order->customer }}" class="form-control" placeholder="customer">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Product:</strong>
                    <select class="form-select" name="product_id" id="product" onChange="calculatePrice()">
                        <option value="">Select product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $order->product_id ==$product->id ? 'selected' : '' }} data-price = "{{ $product->price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>count:</strong>
                    <input type="text" name="count" value="{{ $order->count }}" id="count" class="form-control count" placeholder="count" oninput="calculatePrice()">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>comment:</strong>
                    <input type="text" name="comment" value="{{ $order->comment }}" class="form-control" placeholder="comment">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>price:</strong>
                    <span id="price">{{ $order->price }}</span>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    <script>
        function calculatePrice(){
            price.innerText = count.value * product.options[product.selectedIndex].dataset.price;
        }
    </script>
@endsection
