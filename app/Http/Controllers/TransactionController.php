<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function transaction()
    {
        return view('transaction');
    }

    public function item($barcode)
    {
        $book = Book::where('barcode', $barcode)->first();

        if ($book)
            return $book;
        else
            return null;
    }

    public function store(Request $request)
    {
        $book = Book::where('barcode', $request->barcode)->first();


        if ($book) {

            if ($request->type == 1) {
                $profit = floatval($book->selling_price) - floatval($book->retail_price);

                Transaction::create([
                    'transaction_type_id' => $request->type,
                    'book_id' => $book->id,
                    'quantity' => $request->quantity,
                    'profit' => $profit
                ]);
            } else {
                Transaction::create([
                    'transaction_type_id' => $request->type,
                    'book_id' => $book->id,
                    'quantity' => $request->quantity,
                    'profit' => 0
                ]);
            }

            $quantityLeft = intval($book->quantity) - intval($request->quantity);

            $book->update([
                'quantity' => $quantityLeft
            ]);

            return response()->json(['code' => 200]);
        }
        return response()->json(['code' => 500]);
    }

    public function charts()
    {
        $weeks = CalendarService::getWeeks()['schedule'];

        $monthsArray = [];
        for ($count = 1; $count <= 12; $count++) {
            $monthlyTransaction = Transaction::whereMonth('created_at', $count)
                ->where('transaction_type_id', 1)
                ->sum('profit');
            $monthsArray[] = $monthlyTransaction;
        }

        $weeksArray = [];
        $weeksPerMonthCount = count(collect($weeks)->first());
        for ($week = 0; $week < $weeksPerMonthCount; $week++) {
            $startOfWeek = Carbon::now()->startOfMonth()->addWeek($week)->startOfWeek();
            $endOfWeek = Carbon::now()->startOfMonth()->addWeek($week)->endOfWeek();

            $weeksArray[] = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->where('transaction_type_id', 1)
                ->sum('profit');
        }

        return response()->json([
            'monthlyProfits' => $monthsArray,
            'weekNumber' => range(1, $weeksPerMonthCount),
            'weeklyProfits' => $weeksArray
        ]);
    }
}
