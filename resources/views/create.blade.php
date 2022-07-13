@extends('layout')

@section('content')

<div>
    <h2>Add new book</h2>
</div>

@if ($message = Session::get('fail'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
@endif

<div>
    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        <div>
            <div>
                <div>
                    <strong>Published date:</strong>
                    <input type="date" name="date" class="form-control" placeholder="Published Date">
                </div>
            </div>
            <div>
                <div>
                    <strong>Name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Name">
                </div>
            </div>
            <div>
                <div>
                    <strong>Author:</strong>
                    <input type="text" name="author" class="form-control" placeholder="Author">
                </div>
            </div>
            <div>
                    <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
</div>
<div class="pull-right">
    <a class="btn btn-success" href="{{ route('books.index') }}"> Back</a>
</div>
@endsection