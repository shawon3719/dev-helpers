<?php

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

/**
 * Display pagination summery
 *
 * @param int $total_data
 * @param int $data_per_page
 * @param int $current_page
 */
function get_pagination_summary($data)
{

    $total_item = $data->total();
    $item_per_page = $data->perPage();
    $current_page = $data->currentPage();

    $pagination_summary = "";
    if ($total_item > $item_per_page) {
        if ($current_page == 1) {
            $pagination_summary = "Showing 1 to $item_per_page records of $total_item";
        } else {
            if (($total_item - $current_page * $item_per_page) > $item_per_page) {
                $from = ($current_page - 1) * $item_per_page + 1;
                $to = $current_page * $item_per_page;
                $pagination_summary = "Showing $from to $to records of $total_item";
            } else {
                $from = ($current_page - 1) * $item_per_page + 1;
                $to = ($total_item - ($current_page - 1) * $item_per_page) + ($current_page - 1) * $item_per_page;
                $pagination_summary = "Showing $from to $to records of $total_item";
            }
        }
    }
    return $pagination_summary;
}

function getInitialism($text){
    
    $words = preg_split("/\s+/", $text);
    
    $initialism = "";

    foreach ($words as $w) {
        $initialism .= $w[0];
    }

    return $initialism;
}

function getAmountInWords( $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $taka = implode('', array_reverse($str));
        $paisa = ($decimal > 0) ? "and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paisa' : '';
        return ucwords(strtolower(($taka ? $taka . 'Taka ' : '') . $paisa)).' Only';
    }

function humanFileSize($size,$unit="") {
    if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
    if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
    if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
    return number_format($size)." bytes";
}

function diff_date_for_humans(Carbon $date) : string
{
    return (new Jenssegers\Date\Date($date->timestamp))->ago();
}
function diff_string_for_humans($stringDate) : string
{
    $date = Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $stringDate);
    return (new Jenssegers\Date\Date($date))->ago();
}


function scannerTableLabel($stringDate) : string
{
    $now = Jenssegers\Date\Date::now();
    $date = Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $stringDate);
    $printDate = (new Jenssegers\Date\Date($date))->ago();
    $color = $now > $date ? 'info' : 'danger';

    $res = '<span class="badge badge-'.$color.'" style="color:white;">SCANNER: ';
    $res .= $printDate ;
    $res .= '</span>';

    return $res;
}

function timeFormat($time){
    if (strpos($time, 'AM') !== false) {
        return str_replace(" AM", "",$time, $count);
    }
    if (strpos($time, 'PM') !== false) {
        return str_replace(" PM", "",$time, $count);
    }
}