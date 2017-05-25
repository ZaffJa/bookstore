<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view('book');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'barcode' => 'required|unique:books|integer',
            'title' => 'required|max:255',
            'publisher' => 'required|max:255',
            'quantity' => 'required|integer',
            'retail_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
        ]);

        $book = Book::create($request->all());

        return back()->with('success', 'Added ' . $book->title);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'barcode' => 'required|integer',
            'title' => 'required|max:255',
            'publisher' => 'required|max:255',
            'quantity' => 'required|integer',
            'retail_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ]);

        $book = Book::where('barcode',$request->barcode)->first();
        $book->update($request->all());

        return back()->with('success', 'Updated ' . $book->title);
    }

    public function delete($id)
    {
        $book = Book::find($id);
        $title = $book->title;
        $book->delete();

        return back()->with('success', 'Deleted ' . $title);
    }

}
