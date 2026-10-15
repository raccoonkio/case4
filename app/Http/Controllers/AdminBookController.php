<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBookController extends Controller
{
    public function index(): View
    {
        return view('admin.books.index', [
            'books' => Book::query()->orderBy('title')->get(),
            'expiringRentals' => Order::query()
                ->with(['book', 'user'])
                ->where('order_type', 'rental')
                ->where('status', 'active')
                ->whereBetween('ends_at', [now(), now()->addDays(7)])
                ->orderBy('ends_at')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.books.form', ['book' => new Book, 'formTitle' => 'Добавить книгу']);
    }

    public function store(Request $request): RedirectResponse
    {
        Book::create($this->validatedData($request));

        return redirect()->route('admin.books.index')->with('status', 'Книга добавлена в библиотеку.');
    }

    public function edit(Book $book): View
    {
        return view('admin.books.form', ['book' => $book, 'formTitle' => 'Изменить книгу']);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $book->update($this->validatedData($request));

        return redirect()->route('admin.books.index')->with('status', 'Данные книги обновлены.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->orders()->exists()) {
            return back()->withErrors(['book' => 'Книгу с историей заказов нельзя удалить. Измените её статус на архивный.']);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('status', 'Книга удалена.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:120'],
            'published_year' => ['required', 'integer', 'between:1000,2100'],
            'description' => ['required', 'string', 'max:2000'],
            'preview_path' => ['nullable', 'string', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'rental_price_14_days' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'rental_price_30_days' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'rental_price_90_days' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'status' => ['required', 'in:published,draft,archived'],
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        return $validated;
    }
}
