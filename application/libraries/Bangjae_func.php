<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bangjae_func {
    
// Can select Bangjae year that searching directory. 
    // $peri = SHRT or MEDM, $season = SPRING or WINTER (directory name)
    // public function getBangjaeYear($peri, $season) {

    //     $dataPath = $this->datafile_dir . $peri . "/". $season;
    //     $yearArr = $this->common_func->getDirectoryYear($dataPath);

    //     return($yearArr);
    // }
    
public function getDateSelctBoxArray($bangjae_date) {

    $result_arr = array();

    foreach ($bangjae_date as $bd) {

        $yyyy = substr($bd,0,4);
        $mm = substr($bd,4,2);
        if( !array_key_exists($yyyy, $result_arr) ) {
            $result_arr[$yyyy] = [$mm];
        } else {
            array_push($result_arr[$yyyy], $mm);
        }
    }

    return $result_arr;
}


// 시작 월과 끝 월 기입 메서드 (파일이름의 끝 날짜 기입용)
public function getDateBangjae($bangjae_date, $bangjae_season) {
    $monRangeArr = array();
    
    $bangjae_yyyy = substr($bangjae_date, 0, 4);
    $bangjae_mm = substr($bangjae_date, 4, 2);

    $season_arr = $bangjae_season[$bangjae_mm];

    $file_date_name = $bangjae_yyyy . $season_arr[0] . "_";
        if( $bangjae_mm == "11" ) {
            $plus_yyyy = strval( (int)$bangjae_yyyy +1 );
            $file_date_name .= $plus_yyyy;
        } else {
            $file_date_name .= $bangjae_yyyy;
        }
        $file_date_name .= $season_arr[1];

    $utc_info = [
        'ymInfo' => $bangjae_date,
        'data' => $file_date_name
    ];
    
    array_push($monRangeArr, $utc_info);

    return $monRangeArr;
}


// 방재-공간분포 시 사용 : 시작 월과 끝 월 기입 메서드 (파일이름의 끝 날짜 기입용)
public function getDateBangjaeMap($bangjae_date, $bangjae_season, $init_hour) {
    $monRangeArr = array();
    
    $bangjae_yyyy = substr($bangjae_date, 0, 4);
    $bangjae_mm = substr($bangjae_date, 4, 2);

    $season_arr = $bangjae_season[$bangjae_mm];

    foreach ($init_hour as $utc) {
        
        // 00UTC+12UTC의 경우 00#12
        $targUTC = explode("#" , $utc);
        $strtUTC = $targUTC[0];
        $endUTC = $targUTC[1];
        $infoUTC = $strtUTC;
            if( $strtUTC != $endUTC ) {
                $infoUTC = $strtUTC . "-" . $endUTC;    
            }
        
        $file_date_name = $bangjae_yyyy . $season_arr[0] . $strtUTC . "_";
            if( $bangjae_mm == "11" ) {
                $plus_yyyy = strval( (int)$bangjae_yyyy +1 );
                $file_date_name .= $plus_yyyy;
            } else {
                $file_date_name .= $bangjae_yyyy;
            }
            $file_date_name .= $season_arr[1] . $endUTC;

        $utc_info = [
            'utcInfo' => $infoUTC . "UTC",
            'ymInfo' => $bangjae_date,
            'data' => $file_date_name
        ];
        
        array_push($monRangeArr, $utc_info);
    }

    return $monRangeArr;
}



// 계절별 : 시작 월과 끝 월 기입 메서드 (파일이름의 끝 날짜 기입용)
public function getDateSeason($season_date) {
    $monRangeArr = array();
    
    $season_yyyy = substr($season_date, 0, 4);
    $season_mm = substr($season_date, 4, 2);

    // 선택 월의 사이 월 구하기.
    $start = ( new DateTime($season_date . "01")) -> modify('first day of this month');
    $start_date = $start->format("Ymd");
    $end = $this->getCalcDatePlusMonth($start, "2");
    $lastDay = ( new DateTime($end->format("Ymd"))) -> modify('last day of this month');

    // $tmp_fo = $start_date . "_" . $lastDay->format("Ymd");
    // return $tmp_fo;


    // // $season_arr = $bangjae_season[$bangjae_mm];

    $file_date_name = $start_date . "_" . $lastDay->format("Ymd");;

    $ym_info = [
        'ymInfo' => $season_date,
        'data' => $file_date_name
    ];
    
    array_push($monRangeArr, $ym_info);

    return $monRangeArr;
}




// 계절별 공간분포 : 시작 월과 끝 월 기입 메서드 (파일이름의 끝 날짜 기입용)
public function getDateSeasonMap($season_date, $init_hour) {
    $monRangeArr = array();
    
    $season_yyyy = substr($season_date, 0, 4);
    $season_mm = substr($season_date, 4, 2);

    // 선택 월의 사이 월 구하기.
    $start = ( new DateTime($season_date . "01")) -> modify('first day of this month');
    $start_date = $start->format("Ymd");
    $end = $this->getCalcDatePlusMonth($start, "2");
    $lastDay = ( new DateTime($end->format("Ymd"))) -> modify('last day of this month');

    // $tmp_fo = $start_date . "_" . $lastDay->format("Ymd");
    // return $tmp_fo;


    // // $season_arr = $bangjae_season[$bangjae_mm];

    foreach ($init_hour as $utc) {
        
        // 00UTC+12UTC의 경우 00#12
        $targUTC = explode("#" , $utc);
        $strtUTC = $targUTC[0];
        $endUTC = $targUTC[1];
        $infoUTC = $strtUTC;
            if( $strtUTC != $endUTC ) {
                $infoUTC = $strtUTC . "-" . $endUTC;    
            }
        
        $file_date_name = $start_date . $strtUTC . "_" . $lastDay->format("Ymd") . $endUTC;

        $utc_info = [
            'utcInfo' => $infoUTC . "UTC",
            'ymInfo' => $season_date,
            'data' => $file_date_name
        ];
        
        array_push($monRangeArr, $utc_info);
    }

    return $monRangeArr;
}



// 전체기간 공간분포 : 시작 월과 끝 월 기입 메서드 (파일이름의 끝 날짜 기입용)
public function getDateAllmonMap($init_hour, $allmonth_start, $allmonth_end) {
    $monRangeArr = array();
    
    foreach ($init_hour as $utc) {
        
        // 00UTC+12UTC의 경우 00#12
        $targUTC = explode("#" , $utc);
        $strtUTC = $targUTC[0];
        $endUTC = $targUTC[1];
        $infoUTC = $strtUTC;
            if( $strtUTC != $endUTC ) {
                $infoUTC = $strtUTC . "-" . $endUTC;    
            }
        
        $file_date_name = $allmonth_start . $strtUTC . "_" . $allmonth_end . $endUTC;

        $utc_info = [
            'utcInfo' => $infoUTC . "UTC",
            'ymInfo' => substr($allmonth_start, -2),
            'data' => $file_date_name
        ];
        
        array_push($monRangeArr, $utc_info);
    }

    return $monRangeArr;
}


public function getCalcDatePlusMonth($date, $plus_mon) {

    $target_date = $date;
    $calc_date = $target_date->modify('+' . $plus_mon . " months");

    return $calc_date;
}





// // 시작 월과 끝 월 기입 메서드 (파일이름의 끝 날짜 기입용)
// public function getDateBangjae($bangjae_date, $bangjae_season, $init_hour) {
//     $monRangeArr = array();
    
//     $bangjae_yyyy = substr($bangjae_date, 0, 4);
//     $bangjae_mm = substr($bangjae_date, 4, 2);

//     $season_arr = $bangjae_season[$bangjae_mm];

//     foreach ($init_hour as $utc) {
        
//         // 00UTC+12UTC의 경우 00#12
//         $targUTC = explode("#" , $utc);
//         $strtUTC = $targUTC[0];
//         $endUTC = $targUTC[1];
//         $infoUTC = $strtUTC;
//             if( $strtUTC != $endUTC ) {
//                 $infoUTC = $strtUTC . "-" . $endUTC;    
//             }
        
//         $file_date_name = $bangjae_yyyy . $season_arr[0] . $strtUTC . "_";
//             if( $bangjae_mm == "11" ) {
//                 $plus_yyyy = strval( (int)$bangjae_yyyy +1 );
//                 $file_date_name .= $plus_yyyy;
//             } else {
//                 $file_date_name .= $bangjae_yyyy;
//             }
//             $file_date_name .= $season_arr[1] . $endUTC;

//         $utc_info = [
//             'utcInfo' => $infoUTC . "UTC",
//             'ymInfo' => $bangjae_date,
//             'data' => $file_date_name
//         ];
        
//         array_push($monRangeArr, $utc_info);
//     }

//     return $monRangeArr;
// }


    
    
    
}
