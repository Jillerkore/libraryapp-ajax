<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('books.index');
    }

    public function books(Request $request)
    {
        return response()->json(
            [
                'success' => 200, 
                'data' => Book::where('name', 'LIKE', $request->q ? "%{$request->q}%" : '%%')
                                ->get(['id','date','name','author'])
            ], 
            200
        );
    }

    public function book(Request $request)
    {

        $book = Book::where('id', $request->id)->first();

        return response()->json(
            [
                'id'=> $book->id,
                'date'=> $book->date,
                'name'=> $book->name,
                'author'=> $book->author
            ], 
            200
        );
    }
    public function filter(Request $request)
    {
        return response()->json(
            [
                'success' => 200, 
                'data' => Book::name($request->name)->get(['id', 'date', 'name', 'author'])
            ], 
            200
        );
    }

    public function createBook(Request $request) {
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'name' => ['required', 'string', 'min:2'],
            'author' => ['required', 'string', 'min:2']
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> 400, 'message'=> 'Validation Failure! Make sure all the fields are correct.'], 400);
        } else {
            if (!$request->date) {
                $request->merge(['date' => date('Y-m-d')]);
            }
            Book::create($request->all());
            return response()->json(['status'=> 200, 'message'=>'Book created successfully!'], 200);
        }
    }

    public function editBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'date' => 'nullable|date',
            'name' => ['required', 'string', 'min:2'],
            'author' => ['required', 'string', 'min:2']
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=> 400, 'message'=> 'Validation Failure! Make sure all the fields are correct.'], 400);
        } else {

            $book = Book::where('id', $request->id)->first();
            $book->update($request->all());

            return response()->json(['status'=> 200, 'message'=>'Book updated successfully!'], 200);

        }
    }

    public function removeBook(Request $request) {

        $book = Book::where('id', $request->id)->first();
        if ($book) {
            $id = $book->id;
            $book->delete();
            return response()->json(['status'=> 200, 'message' => 'Book successfully deleted.'], 200);
        } else {
            return response()->json(['status'=> 400, 'message' => 'An error occurred while deleting the book.'], 400);
        }
    }
}
