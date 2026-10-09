<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookstoreController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->string('sort')->toString();
        $sortColumns = [
            'category' => 'category',
            'author' => 'author',
            'year' => 'published_year',
        ];

        $books = Book::query()
            ->published()
            ->orderBy($sortColumns[$sort] ?? 'title')
            ->orderBy('title')
            ->get();

        return view('store.index', [
            'books' => $books,
            'sort' => $sort,
        ]);
    }

    public function show(Book $book): View
    {
        abort_unless($book->status === 'published', 404);

        return view('store.show', ['book' => $book]);
    }

    public function account(Request $request): View|RedirectResponse
    {
        $userId = $request->session()->get('user_id');

        if ($userId === null) {
            return redirect()->route('login');
        }

        $user = User::query()
            ->with(['orders.book', 'rentalReminders'])
            ->findOrFail($userId);

        return view('store.account', ['user' => $user]);
    }
}
