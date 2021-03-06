<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Book;
use App\Traits\ApiResponser;
class BookController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $books = Book::all();

        return $this->successResponse($books);
    }

    public function show($book)
    {
        $book = Book::findOrFail($book);

        return $this->successResponse($book);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|min:1',
            'author_id' => 'required|min:1'
        ];

        $this->validate($request, $rules);

        $book = Book::create($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);
    }

    public function update(Request $request, $book)
    {
        $rules = [
            'title' => 'max:255',
            'description' => 'max:255',
            'price' => 'min:1',
            'author_id' => 'min:1'
        ];

        $this->validate($request, $rules);
        
        $book = Book::findOrFail($book);
        $book->fill($request->all());

        if($book->isClean())
            return $this->errorResponse('At least one volume must change', 
            Response::HTTP_UNPROCESSABLE_ENTITY);

        $book->save();
        
        return $this->successResponse($book);
    }

    public function destroy($book)
    {
        $book = Book::findOrFail($book);
        $book->delete();

        return $this->successResponse($book);
    }

    public function byAuthor($author)
    {
        $books = Book::where('author_id', $author)->get();
        return $this->successResponse($books);
    }
}
