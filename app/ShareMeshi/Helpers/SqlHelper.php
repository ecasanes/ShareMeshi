<?php namespace App\ShareMeshi\Helpers;

use Carbon\Carbon;

class SqlHelper
{
    public static function getPaginationSql($page = null, $limit = null)
    {
        if($limit == StatusHelper::NONE){
            return "";
        }

        $limit = SqlHelper::getDefaultLimit($limit);

        if ($page <= 0 || $page == "" || empty($page)) {
            $page = 1;
        }

        $offset = $limit * ($page-1);

        if($page <= 1){
            $offset = 0;
        }

        $paginationSql = " LIMIT {$limit} OFFSET {$offset} ";

        return $paginationSql;

    }

    public static function getPaginationByFilter(array $filter = [])
    {
        $page = 1;

        $limit = SqlHelper::getDefaultLimit();

        if(isset($filter['source'])) {
            if($filter['source'] == 'export') {
                $limit = 'none';
            }
        }

        if(isset($filter['page'])){
            $page = $filter['page'];
        }

        if(isset($filter['limit'])){
            $limit = $filter['limit'];
        }

        $paginationSql = SqlHelper::getPaginationSql($page, $limit);

        return $paginationSql;
    }

    public static function getDefaultLimit($limit = null)
    {
        if(empty($limit) || trim($limit) == ""){
            $limit = 10;
        }

        return $limit;

    }

    public static function getDefaultPage($page = null)
    {
        if(empty($page) || trim($page) == ""){
            $page = 1;
        }

        return $page;

    }

    public static function getLimitByFilter($filter)
    {

        $limit = null;

        if(isset($filter['limit'])){
            $limit = $filter['limit'];
        }

        return SqlHelper::getDefaultLimit($limit);

    }

    public static function getPageByFilter($filter)
    {
        $page = null;

        if(isset($filter['page'])){
            $page = $filter['page'];
        }

        return SqlHelper::getDefaultPage($page);

    }

    public static function getMonthDays($filter){

        $dates = [];

        $from = Carbon::now()->startOfMonth()->toDateString();
        $to = Carbon::now()->endOfMonth()->toDateString();

        if(isset($filter['from'])){
            $from = $filter['from'];
        }

        if(isset($filter['to'])){
            $to = $filter['to'];
        }

        $from = Carbon::parse($from);
        $to = Carbon::parse($to);

        for($tempDate = $from; $tempDate->lte($to); $tempDate->addDay()) {

            $date = $tempDate->format('Y-m-d');
            $dayOfWeek = $tempDate->dayOfWeek;
            $dayCode = $tempDate->format('D');
            $dayString = $tempDate->format('l');

            $dates[] = [
                'date' => $date,
                'weekday' => $dayOfWeek,
                'weekday_code' => $dayCode,
                'weekday_string' => $dayString
            ];
        }

        return $dates;

    }

    public static function getPreviousDateRange($range)
    {

        switch ($range) {
            case 'day':
            case 'daily':
                $range = 'yesterday';
                break;
            case 'week':
            case 'weekly':
                $range = 'last_week';
                break;
            case 'month':
            case 'monthly':
                $range = 'last_month';
                break;
            case 'year':
            case 'yearly':
                $range = 'last_year';
                break;
        }

        return $range;

    }

    public static function getFromAndToByRange($range, $now = null, $rangeDate = null)
    {
        if(!$now){
            $now = Carbon::now();
        }

        if($rangeDate){
            $now = Carbon::parse($rangeDate);
        }

        switch ($range) {
            case 'yesterday':
            case 'last_day':
                $yesterday = $now->subDay();
                $from = $yesterday->StartOfDay()->toDateTimeString();
                $to = $yesterday->endOfDay()->toDateTimeString();
                break;
            case 'last_week':
                $lastWeek = $now->subWeek();
                $from = $lastWeek->startOfWeek()->toDateTimeString();
                $to = $lastWeek->endOfWeek()->toDateTimeString();
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $from = $lastMonth->startOfMonth()->toDateTimeString();
                $to = $lastMonth->endOfMonth()->toDateTimeString();
                break;
            case 'last_year':
                $lastYear = $now->subYear();
                $from = $lastYear->startOfYear()->toDateTimeString();
                $to = $lastYear->endOfYear()->toDateTimeString();
                break;
            case 'last_10_days':
                $from = Carbon::now()->subDays(10)->toDateTimeString();
                $to = $now->toDateTimeString();
                break;
            case 'day':
            case 'daily':
            case 'today':
                $from = $now->startOfDay()->toDateTimeString();
                $to = $now->endOfDay()->toDateTimeString();
                break;
            case 'week':
            case 'weekly':
                $from = $now->startOfWeek()->toDateTimeString();
                $to = $now->endOfWeek()->toDateTimeString();
                break;
            case 'month':
            case 'monthly':
                $from = $now->startOfMonth()->toDateTimeString();
                $to = $now->endOfMonth()->toDateTimeString();
                break;
            case 'year':
            case 'yearly':
                $from = $now->startOfYear()->toDateTimeString();
                $to = $now->endOfYear()->toDateTimeString();
                break;
        }

        $rangeObject = new \stdClass();

        $rangeObject->from = $from;
        $rangeObject->to = $to;
        $rangeObject->rangeDate = $rangeDate;

        return $rangeObject;

    }
}