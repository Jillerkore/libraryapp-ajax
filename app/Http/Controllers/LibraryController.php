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

        $books = Book::latest()->paginate(100);

        return view('index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'name' => ['required', 'string', 'min:2'],
            'author' => ['required', 'string', 'min:2']
        ]);
        // dd($request->all());

        if ($validator->fails()) {
            return redirect()->route('books.create')->with('fail', 'Please recheck the entered fields i.e. date must be between 0-10,000 and title and author must not be empty.');
        } else {
            if ( !$request->date ) {
                $request->merge(['date'=>date('Y-m-d')]);
            }
            Book::create($request->all());
            return redirect()->route('books.index')->with('success', 'Book created successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        return view('edit',compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'name' => ['required', 'string', 'min:2'],
            'author' => ['required', 'string', 'min:2']
        ]);

        if ($validator->fails()) {
            return redirect()->route('books.edit', compact('book'))->with('fail', 'Please recheck the entered fields i.e. date must be between 0-10,000 and title and author must not be empty.');
        }else {

        $book->update($request->all());

        return redirect()->route('books.index')
                        ->with('success','Book updated successfully');            
        }                
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
                        ->with('success','Book deleted successfully');
    }
}
