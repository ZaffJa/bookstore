<?php

use App\Models\Transaction;
use App\Models\Book;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 2; $i++) {
            $type = rand(1, 2);
            $quantity = rand(1, 10);
            $book = Book::inRandomOrder()->first();
            $randomDate = CalendarService::rand_date(Carbon::now()->startOfYear(), Carbon::now()->endOfYear());

            $profit = intval($quantity) * (floatval($book->selling_price) - floatval($book->retail_price));

            Transaction::create([
                'transaction_type_id' => $type,
                'book_id' => $book->id,
                'quantity' => $quantity,
                'profit' => $profit,
                'created_at' => $randomDate,
                'updated_at' => $randomDate
            ]);
        }
    }
}
