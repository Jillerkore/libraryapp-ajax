@extends('layout')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="pull-left">
            <h2>Library Management System</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('books.create') }}"> Create New Book</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
@endif
    
        <table class="table table-bordered">
            <tr>
                <th>Published Date</th>
                <th>Name</th>
                <th>Author</th>
                <th width="180px">Action</th>
            </tr>
            @foreach ($books as $book)
            <tr>
                <td>{{ $book->date }}</td>
                <td>{{ $book->name }}</td>
                <td>{{ $book->author }}</td>
                <td>
                    <form action="{{ route('books.destroy',$book->id) }}" method="POST">
                        <a class="btn btn-primary" href="{{ route('books.edit',$book->id) }}">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
    
        </table>
        {{ $books->links() }}
@endsection