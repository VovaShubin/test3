@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>orders</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('order.create') }}"> Create New order</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>date</th>
                <th>name</th>
                <th>status</th>
                <th>price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>{{ $order->customer }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->price }}</td>
                    <td>
                        <a class="btn btn-info" href="{{ route('order.show',$order->id) }}">Show</a>
                        <a class="btn btn-primary" href="{{ route('order.edit',$order->id) }}">Edit</a>
                        <form action="{{ route('order.destroy',$order->id) }}" method="POST">

                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
