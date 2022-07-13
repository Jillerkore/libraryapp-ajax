@extends('layout')

@section('content')

<div class="pull-up">
    <h2>Edit book</h2>
</div>

@if ($message = Session::get('fail'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
@endif

<div>
    <form action="{{ route('books.update',$book->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <div>
                <div>
                    <strong>Published Date:</strong>
                    <input type="date" name="date" class="form-control" placeholder="date" value = "{{ $book->date }}">
                </div>
            </div>
            <div>
                <div>
                    <strong>Name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Name" value = "{{ $book->name }}">
                </div>
            </div>
            <div>
                <div>
                    <strong>Author:</strong>
                    <input type="text" name="author" class="form-control" placeholder="Author" value = "{{ $book->author }}">
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