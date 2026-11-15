<?php

namespace App\Helpers;

use Carbon\Carbon;

class BengaliDateHelper
{
    /**
     * Mapping of English digits to Bengali digits.
     *
     * @var array<string, string>
     */
    protected static $bengaliNumbers = [
        '0' => '০',
        '1' => '১',
        '2' => '২',
        '3' => '৩',
        '4' => '৪',
        '5' => '৫',
        '6' => '৬',
        '7' => '৭',
        '8' => '৮',
        '9' => '৯'
    ];

    /**
     * Mapping of month numbers to Bengali month names.
     *
     * @var array<int, string>
     */
    protected static $bengaliMonths = [
        1 => 'জানুয়ারি',
        2 => 'ফেব্রুয়ারি',
        3 => 'মার্চ',
        4 => 'এপ্রিল',
        5 => 'মে',
        6 => 'জুন',
        7 => 'জুলাই',
        8 => 'আগস্ট',
        9 => 'সেপ্টেম্বর',
        10 => 'অক্টোবর',
        11 => 'নভেম্বর',
        12 => 'ডিসেম্বর'
    ];


    /**
     * Convert English digits in a string to Bengali digits.
     *
     * @param string $string Input string with digits
     * @return string String with Bengali digits
     */
    public static function toBengali($string)
    {
        return strtr($string, self::$bengaliNumbers);
    }

    /**
     * Alias for toBengali to format numbers.
     *
     * @param string|int $number
     * @return string
     */
    public static function bnNum($number)
    {
        return self::toBengali((string)$number);
    }

    /**
     * Mapping of AM/PM to Bengali equivalents.
     *
     * @var array<string, string>
     */
    protected static $bengaliAmPm = [
        'AM' => 'এএম',
        'PM' => 'পিএম'
    ];

    /**
     * Format a given date into Bengali format (day, month, year).
     *
     * Example: 05 October, 2025 → ০৫ অক্টোবর, ২০২৫
     *
     * @param string|\DateTimeInterface $date Date input
     * @return string Formatted Bengali date
     */
    public static function formatEnglishToBnDate($date)
    {
        $carbonDate = Carbon::parse($date, 'Asia/Dhaka');

        $banglaDays = [
            'রবিবার',
            'সোমবার',
            'মঙ্গলবার',
            'বুধবার',
            'বৃহস্পতিবার',
            'শুক্রবার',
            'শনিবার'
        ];

        $dayName = $banglaDays[$carbonDate->dayOfWeek];
        $day = self::toBengali($carbonDate->format('d'));
        $month = self::$bengaliMonths[$carbonDate->format('n')];
        $year = self::toBengali($carbonDate->format('Y'));

        return "{$dayName}, {$day} {$month} {$year}";
    }

    /**
     * Format a given date into Bengali format without weekday.
     *
     * Example: 05 October, 2025 → ০৫ অক্টোবর, ২০২৫
     *
     * @param string|\DateTimeInterface $date Date input
     * @return string Formatted Bengali date
     */
    public static function formatBengaliDate($date)
    {
        $carbonDate = Carbon::parse($date);
        $day = self::toBengali($carbonDate->format('d'));
        $month = self::$bengaliMonths[$carbonDate->format('n')];
        $year = self::toBengali($carbonDate->format('Y'));

        return "{$day} {$month}, {$year}";
    }

    /**
     * Format a given date and time into Bengali format.
     *
     * Example: 05 October, 2025 07:45 PM → ০৫ অক্টোবর, ২০২৫ ০৭:৪৫ পিএম
     *
     * @param string|\DateTimeInterface $date Date input
     * @return string Formatted Bengali date with time
     */
    public static function formatBengaliDateTime($date)
    {
        $carbonDate = Carbon::parse($date);
        $day = self::toBengali($carbonDate->format('d'));
        $month = self::$bengaliMonths[$carbonDate->format('n')];
        $year = self::toBengali($carbonDate->format('Y'));

        $hour = self::toBengali($carbonDate->format('h')); // 12-hour format
        $minute = self::toBengali($carbonDate->format('i'));
        // $second = self::toBengali($carbonDate->format('s'));
        $amPm = self::$bengaliAmPm[$carbonDate->format('A')]; // AM or PM

        return "{$day} {$month} {$year}, {$hour}:{$minute} {$amPm}";
    }

    /*********************************************************************************
     *********************************************************************************
     * Benagla month and year
     * @return: ৮ পৌষ ১৪৩২
     * *****************************************************************************
     * */
    public static function formatBanglaDateMonthYear(string $datetime): string
    {
        $date = Carbon::parse($datetime, 'Asia/Dhaka');

        // 6 AM cutoff: if current time is before 6 AM, consider previous day
        if ((int)$date->format('H') < 6) {
            $date->subDay();
        }

        $bnMonths = [
            'বৈশাখ',
            'জ্যৈষ্ঠ',
            'আষাঢ়',
            'শ্রাবণ',
            'ভাদ্র',
            'আশ্বিন',
            'কার্তিক',
            'অগ্রহায়ণ',
            'পৌষ',
            'মাঘ',
            'ফাল্গুন',
            'চৈত্র'
        ];

        $bnNum = fn($n) => strtr($n, [
            '0' => '০',
            '1' => '১',
            '2' => '২',
            '3' => '৩',
            '4' => '৪',
            '5' => '৫',
            '6' => '৬',
            '7' => '৭',
            '8' => '৮',
            '9' => '৯'
        ]);

        $year = (int)$date->year;
        $month = (int)$date->month;
        $day = (int)$date->day;

        $bnYear = $year - 593;
        if ($month < 4 || ($month == 4 && $day < 14)) {
            $bnYear--;
        }

        // Bangla year start
        $bnYearStart = Carbon::create($bnYear + 593, 4, 14, 0, 0, 0, 'Asia/Dhaka');

        if ($date->lt($bnYearStart)) {
            $bnYearStart->subYear();
        }

        // Calculate days passed as integer
        $daysPassed = $bnYearStart->copy()->startOfDay()->diffInDays($date->copy()->startOfDay());

        $monthDays = [31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 30, 30];

        $monthIndex = 0;
        while ($daysPassed >= $monthDays[$monthIndex]) {
            $daysPassed -= $monthDays[$monthIndex];
            $monthIndex++;
        }

//        $bnDay = $daysPassed + 1;
        $bnDay = $daysPassed;
        $bnMonth = $bnMonths[$monthIndex];

        return $bnNum($bnDay) . ' ' . $bnMonth . ' ' . $bnNum($bnYear);
//        return '৩০ পৌষ ' . $bnNum($bnYear);
    }
}
