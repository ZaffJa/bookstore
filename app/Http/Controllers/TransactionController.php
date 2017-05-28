<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
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
        $weeks = $this->getWeeks()['schedule'];

        $monthsArray = [];
        for ($count = 1; $count <= 12; $count++) {
            $monthlyTransaction = Transaction::whereMonth('created_at', $count)->sum('profit');
            $monthsArray[] = $monthlyTransaction;
        }

        $weeksArray = [];
        $weeksPerMonthCount = count(collect($weeks)->first());
        for ($week = 0; $week < $weeksPerMonthCount; $week++) {
            $startOfWeek = Carbon::now()->startOfMonth()->addWeek($week)->startOfWeek();
            $endOfWeek = Carbon::now()->startOfMonth()->addWeek($week)->endOfWeek();

            $weeksArray[] = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('profit');
        }

        return response()->json([
            'monthlyProfits' => $monthsArray,
            'weekNumber' => range(1, $weeksPerMonthCount),
            'weeklyProfits' => $weeksArray
        ]);
    }

    public function transactionsGenerator()
    {
        $dates = [];
        for ($i = 0; $i < 100; $i++) {
            $type = rand(1, 2);
            $quantity = rand(1, 10);
            $book = Book::inRandomOrder()->first();
            $randomDate = $this->rand_date(Carbon::now()->startOfYear(), Carbon::now()->endOfYear());

            $profit = intval($quantity) * (floatval($book->selling_price) - floatval($book->retail_price));

            Transaction::create([
                'transaction_type_id' => $type,
                'book_id' => $book->id,
                'quantity' => $quantity,
                'profit' => $profit,
                'created_at' => $randomDate,
                'updated_at' => $randomDate
            ]);
            $dates[] = $randomDate;
        }

        return $dates;
    }

    function rand_date($min_date, $max_date)
    {
        /* Gets 2 dates as string, earlier and later date.
           Returns date in between them.
        */

        $min_epoch = strtotime($min_date);
        $max_epoch = strtotime($max_date);

        $rand_epoch = rand($min_epoch, $max_epoch);

        return date('Y-m-d H:i:s', $rand_epoch);
    }

    public function getWeeks($today = null, $scheduleMonths = 6)
    {

        $today = !is_null($today) ? Carbon::createFromFormat('Y-m-d', $today) : Carbon::now();

        $startDate = Carbon::instance($today)->startOfMonth()->startOfWeek()->subDay(); // start on Sunday
        $endDate = Carbon::instance($startDate)->addMonths($scheduleMonths)->endOfMonth();
        $endDate->addDays(6 - $endDate->dayOfWeek);

        $epoch = Carbon::createFromTimestamp(0);
        $firstDay = $epoch->diffInDays($startDate);
        $lastDay = $epoch->diffInDays($endDate);

        $week = 0;
        $monthNum = $today->month;
        $yearNum = $today->year;
        $prevDay = null;
        $theDay = $startDate;
        $prevMonth = $monthNum;

        $data = array();

        while ($firstDay < $lastDay) {

            if (($theDay->dayOfWeek == Carbon::SUNDAY) && (($theDay->month > $monthNum) || ($theDay->month == 1))) $monthNum = $theDay->month;
            if ($prevMonth > $monthNum) $yearNum++;

            $theMonth = Carbon::createFromFormat("Y-m-d", $yearNum . "-" . $monthNum . "-01")->format('F Y');

            if (!array_key_exists($theMonth, $data)) $data[$theMonth] = array();
            if (!array_key_exists($week, $data[$theMonth])) $data[$theMonth][$week] = array(
                'day_range' => '',
            );

            if ($theDay->dayOfWeek == Carbon::SUNDAY) $data[$theMonth][$week]['day_range'] = sprintf("%d-", $theDay->day);
            if ($theDay->dayOfWeek == Carbon::SATURDAY) $data[$theMonth][$week]['day_range'] .= sprintf("%d", $theDay->day);

            $firstDay++;
            if ($theDay->dayOfWeek == Carbon::SATURDAY) $week++;
            $theDay = $theDay->copy()->addDay();
            $prevMonth = $monthNum;
        }

        $totalWeeks = $week;

        return array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalWeeks' => $totalWeeks,
            'schedule' => $data,
        );

    }


}
