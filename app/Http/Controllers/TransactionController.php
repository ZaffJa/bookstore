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

            if($request->type == 1) {
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
        $months = [
            "January", "February", "March", "April", "May", "June", "July", "August", "October", "November", "December"
        ];

        $monthOutput = array_slice($months, 0, Carbon::now()->month);
        $weeks = $this->getWeeks()['schedule'];


        $monthlyTransaction = Transaction::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();

        $weeksPerMonthCount = count(collect($weeks)->first());
//        $weeksPerMonthCount = count($collectWeeks->first());


        for ($week = 0; $week < $weeksPerMonthCount; $week++) {
            $thisWeek = Carbon::now()->startOfMonth()->addWeek($week);

            return $thisWeek;
        }

//        return $monthlyTransaction->all();
//        return $monthlyTransaction->all();

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
