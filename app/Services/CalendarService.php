<?php
namespace App\Services;


use Carbon\Carbon;

class CalendarService
{

    public static function rand_date($min_date, $max_date)
    {
        /* Gets 2 dates as string, earlier and later date.
           Returns date in between them.
        */

        $min_epoch = strtotime($min_date);
        $max_epoch = strtotime($max_date);

        $rand_epoch = rand($min_epoch, $max_epoch);

        return date('Y-m-d H:i:s', $rand_epoch);
    }


    public static function getWeeks($today = null, $scheduleMonths = 6)
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