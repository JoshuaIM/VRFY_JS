<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common_func {
    
    
// 디렉토리로 날짜 검색하여 최신 날짜 가져오는 메서드.    
    public function getDirectoryDate($dataPath) {

        $dir_arr = $this->getDateDirectoryArray($dataPath);
        
        $latest = max($dir_arr);
        $yyyy = substr( strval($latest), 0, 4);
        $mm = substr( strval($latest), -2);

        $latest_date = $yyyy . "-" . $mm;
        
        return $latest_date;
    }



// YYYYMM 형식의 데이터 디렉토리 배열화 : 배열 반납.
    public function getDateDirectoryArray($path) {

        $dirs = glob($path . '/*', GLOB_ONLYDIR);
        
        $dir_arr = array();
        foreach ($dirs as $d) {
            $d_all = explode("/", $d);
            $d_name = $d_all[sizeof($d_all)-1];
                // 디렉토리 명(YYYYMM)이 6자리 && 숫자 인 것만 
                if( strlen($d_name) == 6 && is_numeric($d_name) ) {
                    array_push($dir_arr, intval($d_name));
                }
        }
        rsort($dir_arr);

        return $dir_arr;
    }

    
    
// 검증페이지 sub sidemenu(OPTION 메뉴: 좌측 두번째) 상단 현재 선택 상태 가이드 표출용.
    public function getVrfyTypeName($vrfyType) {
        $vt = explode("_", $vrfyType);
        
        $vtArr = array();
        switch ( $vt[0] ) {
            case( $vt[0] == "shrt" ): array_push($vtArr, "단기"); break;
            case( $vt[0] == "medm" ): array_push($vtArr, "중기"); break;
            // case( $vt[0] == "ssps" && $vt[1] == "shrt" ): array_push($vtArr, "단기(산악)"); break;
        }
        switch ( $vt[1] ) {
            case( $vt[1] == "tb" ): array_push($vtArr, "집계표"); break;
            case( $vt[1] == "ts" || $vt[2] == "ts" ): array_push($vtArr, "시계열"); break;
            case( $vt[1] == "map" ): array_push($vtArr, "공간분포"); break;
        }
        switch ( $vt[2] ) {
            case( $vt[2] == "stn" || $vt[3] == "stn" ): array_push($vtArr, "지점"); break;
            case( $vt[2] == "grd" ): array_push($vtArr, "격자"); break;
        }
        
        return $vtArr;
    }
// getVrfyTypeName() 복제 - 산악용.
    public function getSspsTypeName($vrfyType) {
        $vt = explode("_", $vrfyType);
        
        $vtArr = array();
        switch ( $vt[1] ) {
            case( $vt[1] == "shrt" ): array_push($vtArr, "산악"); break;
            case( $vt[1] == "medm" ): array_push($vtArr, "중기"); break;
        }
        switch ( $vt[2] ) {
            case( $vt[2] == "tb" ): array_push($vtArr, "집계표"); break;
            case( $vt[2] == "ts" || $vt[2] == "ts" ): array_push($vtArr, "시계열"); break;
            case( $vt[2] == "map" ): array_push($vtArr, "공간분포"); break;
        }
        switch ( $vt[3] ) {
            case( $vt[3] == "stn" ): array_push($vtArr, "지점"); break;
            case( $vt[3] == "grd" ): array_push($vtArr, "격자"); break;
        }
        
        return $vtArr;
    }

// getVrfyTypeName() 복제 - 예보활용도용.
    public function getGemdTypeName($vrfyType) {
        $vt = explode("_", $vrfyType);
        
        $vtArr = array();
        switch ( $vt[1] ) {
            case( $vt[1] == "ts" ): array_push($vtArr, "시계열"); break;
            case( $vt[1] == "map" ): array_push($vtArr, "공간분포"); break;
        }
        switch ( $vt[2] ) {
            case( $vt[2] == "utilize" ): array_push($vtArr, "활용도"); break;
            case( $vt[2] == "similarity" ): array_push($vtArr, "유사도"); break;
            case( $vt[2] == "accuracy" ): array_push($vtArr, "정확도"); break;
        }
        
        return $vtArr;
    }


    
// 요소(변수)별 검증지수 선택 배열 만드는 메서드.
    public function getVrfyTech($vn) {
        $vrfyTech = array();
        if( $vn == "T3H" ||  $vn == "TMX" ||  $vn == "TMN" || $vn == "T1H" ) {
            $vrfyTech = [
                // "data_vrfy" => ["CNT1", "CNT2", "CNT3", "BIAS", "MAEE", "RMSE"],
                // "txt_vrfy" => ["0~2", "2~4", "4~ ", "BIAS", "MAE", "RMSE"],
                // "title_vrfy" => ["오차빈도(0~2)", "오차빈도(2~4)", "오차빈도(4~)", "BIAS", "MAE", "RMSE"]
                "data_vrfy" => ["BIAS", "MAEE", "RMSE", "CNT1", "CNT2", "CNT3"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE", "0~2", "2~4", "4~ "],
                "title_vrfy" => ["BIAS", "MAE", "RMSE", "오차빈도(0~2)", "오차빈도(2~4)", "오차빈도(4~)"]
            ];
        } else if( $vn == "REH" ) {
            $vrfyTech = [
                // "data_vrfy" => ["CNT1", "CNT2", "CNT3", "BIAS", "MAEE", "RMSE"],
                // "txt_vrfy" => ["0~10", "10~20", "20~ ", "BIAS", "MAE", "RMSE"],
                // "title_vrfy" => ["오차빈도(0~10)", "오차빈도(10~20)", "오차빈도(20~)", "BIAS", "MAE", "RMSE"]
                "data_vrfy" => ["BIAS", "MAEE", "RMSE", "CNT1", "CNT2", "CNT3"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE", "0~10", "10~20", "20~ "],
                "title_vrfy" => ["BIAS", "MAE", "RMSE", "오차빈도(0~10)", "오차빈도(10~20)", "오차빈도(20~)"]
            ];
        } else if( $vn == "WSD" ) {
            $vrfyTech = [
                "data_vrfy" => ["BIAS", "MAEE", "RMSE"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE"],
                "title_vrfy" => ["BIAS", "MAE", "RMSE"]
            ];
        } else if( $vn == "PTY" ) {
            $vrfyTech = [
                "data_vrfy" => ["ACCC", "BIAS", "CSII", "FARR", "HSSS", "PODD"],
                // "txt_vrfy" => ["ACC", "BIAS", "CSI", "FAR", "HSS", "POD"],
                // "title_vrfy" => ["ACC", "BIAS", "CSI", "FAR", "HSS", "POD"]
                "txt_vrfy" => ["ACC", "FBI", "CSI", "FAR", "HSS", "POD"],
                "title_vrfy" => ["ACC", "FBI", "CSI", "FAR", "HSS", "POD"]
            ];
        } else if( $vn == "SKY" || $vn == "VEC" ) {
            $vrfyTech = [
                "data_vrfy" => ["HSSS", "PCC0", "PCC1"],
                "txt_vrfy" => ["HSS", "PC", "PC(1구간차)"],
                "title_vrfy" => ["HSS", "PC", "PC(1구간차)"]
            ];
        } else if( $vn == "POP" ) {
            $vrfyTech = [
                "data_vrfy" => ["BRS0", "BRS1", "BRSS"],
                "txt_vrfy" => ["무강수", "유강수", "all"],
                "title_vrfy" => ["무강수", "유강수", "all"]
            ];
        } else if( $vn == "RN3" ) {
            // $vt = ["BIS", "CSI", "ETS"];
            $vt = ["CSI", "BIS", "ETS"];
            $vt_arr = array();
            $txt = ["1mm", "10mm", "60mm", "90mm"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<5; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "BIAS";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt_arr,
                "title_vrfy" => $title_arr
            ];
        } else if( $vn == "RN1" ) {
            // $vt = ["BIS", "CSI", "ETS"];
            // $vt = ["CSI", "BIS", "ETS"];
            $vt = ["CSI", "BIS", "ETS", "ACC", "FAR", "HSS", "POD"];
            $vt_arr = array();
            // $txt = ["1mm", "10mm", "60mm", "90mm"];
            // $txt = ["0.5mm", "5mm", "20mm", "50mm"];
            // $txt = ["0.1mm", "0.5mm", "1mm", "10mm"];
            $txt = ["0.1~3mm", "3~15mm", "0.1mm~", "0.5mm~", "1mm~", "3mm~", "10mm~", "15mm~"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                // for( $v=1; $v<5; $v++) {
                for( $v=1; $v<sizeof($txt)+1; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "FBI";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                // "txt_vrfy" => $txt_arr,
                "txt_vrfy" => $txt,
                "title_vrfy" => $title_arr
            ];
        } else if( $vn == "SN1" ) {
            $vt = ["CSI", "BIS", "ETS"];
            $vt_arr = array();
            $txt = ["0.1~0.5cm", "0.5~1cm", "0.1cm~", "0.5cm~", "1cm~", "5cm~", "10cm~"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                // for( $v=1; $v<5; $v++) {
                for( $v=1; $v<sizeof($txt)+1; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "FBI";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                // "txt_vrfy" => $txt_arr,
                "txt_vrfy" => $txt,
                "title_vrfy" => $title_arr
            ];
        } else if( $vn == "RN6" || $vn == "R12" ) {
            // $vt = ["BIS", "CSI", "ETS"];
            $vt = ["CSI", "BIS", "ETS"];
            $vt_arr = array();
            $txt = ["1mm", "10mm", "70mm", "1100mm"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<5; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "BIAS";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt_arr,
                "title_vrfy" => $title_arr
            ];
        // } else if( $vn == "SN3" || $vn == "SN6" || $vn == "S12" || $vn == "SN1" ) {
        } else if( $vn == "SN3" || $vn == "SN6" || $vn == "S12" ) {
            // $vt = ["BIS", "CSI", "ETS"];
            $vt = ["CSI", "BIS", "ETS"];
            $vt_arr = array();
            $txt = ["0.1cm", "1cm", "5cm", "10cm"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<5; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "BIAS";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt_arr,
                "title_vrfy" => $title_arr
            ];
        }
        
        return $vrfyTech;
    }

// 단기 적설이 SN3 로 변경되어 잠시 사용. : 중기 자료와 중복으로 중기도 동일하게 진행 시 원본 함수와 결합 예정.
// 요소(변수)별 검증지수 선택 배열 만드는 메서드.
    public function getVrfyTechShrt($vn) {
        $vrfyTech = array();
        if( $vn == "TMX" ||  $vn == "TMN" || $vn == "T1H" ) {
            $vrfyTech = [
                "data_vrfy" => ["BIAS", "MAEE", "RMSE", "CNT1", "CNT2", "CNT3"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE", "0~2", "2~4", "4~ "],
                "title_vrfy" => ["BIAS", "MAE", "RMSE", "오차빈도(0~2)", "오차빈도(2~4)", "오차빈도(4~)"]
            ];
        } else if( $vn == "REH" ) {
            $vrfyTech = [
                "data_vrfy" => ["BIAS", "MAEE", "RMSE", "CNT1", "CNT2", "CNT3"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE", "0~10", "10~20", "20~ "],
                "title_vrfy" => ["BIAS", "MAE", "RMSE", "오차빈도(0~10)", "오차빈도(10~20)", "오차빈도(20~)"]
            ];
        } else if( $vn == "WSD" ) {
            $vrfyTech = [
                "data_vrfy" => ["BIAS", "MAEE", "RMSE"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE"],
                "title_vrfy" => ["BIAS", "MAE", "RMSE"]
            ];
        } else if( $vn == "PTY" ) {
            $vrfyTech = [
                "data_vrfy" => ["ACCC", "BIAS", "CSII", "FARR", "HSSS", "PODD"],
                "txt_vrfy" => ["ACC", "FBI", "CSI", "FAR", "HSS", "POD"],
                "title_vrfy" => ["ACC", "FBI", "CSI", "FAR", "HSS", "POD"]
            ];
        } else if( $vn == "SKY" || $vn == "VEC" ) {
            $vrfyTech = [
                "data_vrfy" => ["HSSS", "PCC0", "PCC1"],
                "txt_vrfy" => ["HSS", "PC", "PC(1구간차)"],
                "title_vrfy" => ["HSS", "PC", "PC(1구간차)"]
            ];
        } else if( $vn == "POP" ) {
            $vrfyTech = [
                "data_vrfy" => ["BRS0", "BRS1", "BRSS"],
                "txt_vrfy" => ["무강수", "유강수", "all"],
                "title_vrfy" => ["무강수", "유강수", "all"]
            ];
        } else if( $vn == "RN1" ) {
            $vt = ["CSI", "BIS", "ETS", "ACC", "FAR", "HSS", "POD"];
            $vt_arr = array();
            $txt = ["0.1~3mm", "3~15mm", "0.1mm~", "0.5mm~", "1mm~", "3mm~", "10mm~", "15mm~"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<sizeof($txt)+1; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "FBI";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt,
                "title_vrfy" => $title_arr
            ];
        } else if( $vn == "SN3" ) {
            $vt = ["CSI", "BIS", "ETS", "ACC", "FAR", "HSS", "POD"];
            $vt_arr = array();
            $txt = ["0.1~0.5cm", "0.5~1cm", "0.1cm~", "0.5cm~", "1cm~", "5cm~", "10cm~"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<sizeof($txt)+1; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "FBI";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt,
                "title_vrfy" => $title_arr
            ];
        }
        
        return $vrfyTech;
    }
    
    
    
// 시작 월과 끝 월 사이의 모든 월 구하는 메서드 (파일이름의 끝 날짜 기입용)
    public function getDateRangeArr($startDate, $endDate) {
        $monRangeArr = array();
        
        // 선택 월의 사이 월 구하기.
        $start = ( new DateTime($startDate . "01")) -> modify('first day of this month');
        $end = ( new DateTime($endDate . "01")) -> modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        
        foreach ($period as $dt) {
            // period 월의 마지막 일을 구하기 위함.
            $lastDay = ( new DateTime($dt->format("Ymd"))) -> modify('last day of this month');
                $ym_info = [
                    'ymInfo' => $dt->format("Ym"),
                    'data' => $dt->format("Ymd") . '_' . $lastDay->format("Ymd")
                ];
                array_push($monRangeArr, $ym_info);
            }
            
        return $monRangeArr;
    }



    public function getAllMonthDateRangeArr($dataPath, $var_select, $start_month) {

        $monRangeArr = array();

        $recent = $this->getDirectoryDate($dataPath);
        $recent_date = explode("-", $recent);
        $yyyymm = $recent_date[0] . $recent_date[1];

        $end = ( new DateTime($yyyymm . "01")) -> modify('last day of this month');
        $last_date = $end->format("Ymd");

        $file_date_name = $start_month . "_" . $last_date;

        $ym_info = [
            'ymInfo' => $yyyymm,
            'data' => $file_date_name
        ];

        array_push($monRangeArr, $ym_info);

        return $monRangeArr;
    }



// 현재 공간분포만 사용 : 시작 월과 끝 월 사이의 모든 월 구하는 메서드 (파일이름의 끝 날짜 기입용)
    public function getDateRangeArrMAP($startDate, $endDate, $init_hour) {
        $monRangeArr = array();
        
        // 선택 월의 사이 월 구하기.
        $start = ( new DateTime($startDate . "01")) -> modify('first day of this month');
        $end = ( new DateTime($endDate . "01")) -> modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        
        foreach ($period as $dt) {
            // period 월의 마지막 일을 구하기 위함.
            $lastDay = ( new DateTime($dt->format("Ymd"))) -> modify('last day of this month');
            
            foreach ($init_hour as $utc) {
                
                // 00UTC+12UTC의 경우 00#12
                $targUTC = explode("#" , $utc);
                $strtUTC = $targUTC[0];
                $endUTC = $targUTC[1];
                $infoUTC = $strtUTC;
                    if( $strtUTC != $endUTC ) {
                        $infoUTC = $strtUTC . "-" . $endUTC;    
                    }
                
                $utc_info = [
                    'utcInfo' => $infoUTC . "UTC",
                    'ymInfo' => $dt->format("Ym"),
                    'data' => $dt->format("Ymd") . $strtUTC . '_' . $lastDay->format("Ymd") . $endUTC
                ];
                
                array_push($monRangeArr, $utc_info);
            }
            
        }
        // "yyyymmdd(utc)_yyyymmdd(utc)" 형식의 날짜 값 반환.
        
        return $monRangeArr;
    }

// // 시작 월과 끝 월 사이의 모든 월 구하는 메서드 (파일이름의 끝 날짜 기입용)
//     public function getDateRangeArr($startDate, $endDate, $init_hour) {
//         $monRangeArr = array();
        
//         // 선택 월의 사이 월 구하기.
//         $start = ( new DateTime($startDate . "01")) -> modify('first day of this month');
//         $end = ( new DateTime($endDate . "01")) -> modify('first day of next month');
//         $interval = DateInterval::createFromDateString('1 month');
//         $period = new DatePeriod($start, $interval, $end);
        
//         foreach ($period as $dt) {
//             // period 월의 마지막 일을 구하기 위함.
//             $lastDay = ( new DateTime($dt->format("Ymd"))) -> modify('last day of this month');
            
//             foreach ($init_hour as $utc) {
                
//                 // 00UTC+12UTC의 경우 00#12
//                 $targUTC = explode("#" , $utc);
//                 $strtUTC = $targUTC[0];
//                 $endUTC = $targUTC[1];
//                 $infoUTC = $strtUTC;
//                     if( $strtUTC != $endUTC ) {
//                         $infoUTC = $strtUTC . "-" . $endUTC;    
//                     }
                
//                 $utc_info = [
//                     'utcInfo' => $infoUTC . "UTC",
//                     'ymInfo' => $dt->format("Ym"),
//                     'data' => $dt->format("Ymd") . $strtUTC . '_' . $lastDay->format("Ymd") . $endUTC
//                 ];
                
//                 array_push($monRangeArr, $utc_info);
//             }
            
//         }
//         // "yyyymmdd(utc)_yyyymmdd(utc)" 형식의 날짜 값 반환.
        
//         return $monRangeArr;
//     }
    
    
    
// 모델-기법 표출용(뷰 페이지) 정보 제공 메서드  
// TODO : 향 후 확장성을 고려하여 "별도 파일"을 읽어서 저장하도록 변경 해야한다.(담당자 요청)
    public function setModelCheckbox($data_type) {

        $conf_dir = "./assets/modl_tech_conf/";
        $conf_file = $data_type . "_modl_tech.conf";
        $conf_df = $conf_dir . $conf_file;
        
        if( file_exists($conf_df) ) {
            // 줄 단위 파일 읽기.
            $fileLine = explode("\n", file_get_contents($conf_df));
            
            $modl_tech = strtoupper($data_type);
            
            $tmp_modl = explode(":", preg_replace("/\s+/", "", $fileLine[0]));
            $modl_arr = explode(",", $tmp_modl[1]);

            $md_id = array();
            $md_name = array();
            $tc_id = array();
            $tc_name = array();
            foreach ($modl_arr as $mid) {
                $md_type = explode("#", $mid);
                    array_push($md_id, $md_type[0]); 
                    array_push($md_name, $md_type[1]); 
                
                $mt_id = $modl_tech . "_TECH_" . $md_type[0];
                
                for( $i=1; $i<sizeof($fileLine); $i++ ) {
                    $tmp_tech = explode(":", preg_replace("/\s+/", "", $fileLine[$i]));
                    
                    $tmp_tc_id = array();
                    $tmp_tc_name = array();
                    if( $mt_id == $tmp_tech[0] ) {
                        $tech = explode(",", $tmp_tech[1]);
                        foreach ($tech as $tc) {
                            if($tc) {
                                $tc_arr = explode("#", $tc);
                                array_push($tmp_tc_id, $tc_arr[0]);
                                array_push($tmp_tc_name, $tc_arr[1]);
                            }
                        }
                    array_push($tc_id, $tmp_tc_id);                    
                    array_push($tc_name, $tmp_tc_name);                    
                    }
                }
            }
            
            $modltech_info = [
                "modl_id" => $md_id,
                "modl_name" => $md_name,
                "tech_id" => $tc_id,
                "tech_name" => $tc_name
            ];
            
            return $modltech_info;
            
        } else {
            show_error("Not found conf file. ( " . $conf_file . " )");
        }
            
    }
    
    


// 산악 시간강수량 시간 적설 원래대로 진행 하기 위함
// 요소(변수)별 검증지수 선택 배열 만드는 메서드.
    public function getSspsVrfyTech($vn) {
        $vrfyTech = array();
        if( $vn == "T3H" ||  $vn == "TMX" ||  $vn == "TMN" || $vn == "T1H" ) {
            $vrfyTech = [
                // "data_vrfy" => ["CNT1", "CNT2", "CNT3", "BIAS", "MAEE", "RMSE"],
                // "txt_vrfy" => ["0~2", "2~4", "4~ ", "BIAS", "MAE", "RMSE"],
                // "title_vrfy" => ["오차빈도(0~2)", "오차빈도(2~4)", "오차빈도(4~)", "BIAS", "MAE", "RMSE"]
                "data_vrfy" => ["BIAS", "MAEE", "RMSE", "CNT1", "CNT2", "CNT3"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE", "0~2", "2~4", "4~ "],
                "title_vrfy" => ["BIAS", "MAE", "RMSE", "오차빈도(0~2)", "오차빈도(2~4)", "오차빈도(4~)"]
            ];
        } else if( $vn == "REH" ) {
            $vrfyTech = [
                // "data_vrfy" => ["CNT1", "CNT2", "CNT3", "BIAS", "MAEE", "RMSE"],
                // "txt_vrfy" => ["0~10", "10~20", "20~ ", "BIAS", "MAE", "RMSE"],
                // "title_vrfy" => ["오차빈도(0~10)", "오차빈도(10~20)", "오차빈도(20~)", "BIAS", "MAE", "RMSE"]
                "data_vrfy" => ["BIAS", "MAEE", "RMSE", "CNT1", "CNT2", "CNT3"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE", "0~10", "10~20", "20~ "],
                "title_vrfy" => ["BIAS", "MAE", "RMSE", "오차빈도(0~10)", "오차빈도(10~20)", "오차빈도(20~)"]
            ];
        } else if( $vn == "WSD" ) {
            $vrfyTech = [
                "data_vrfy" => ["BIAS", "MAEE", "RMSE"],
                "txt_vrfy" => ["BIAS", "MAE", "RMSE"],
                "title_vrfy" => ["BIAS", "MAE", "RMSE"]
            ];
        } else if( $vn == "PTY" ) {
            $vrfyTech = [
                "data_vrfy" => ["ACCC", "BIAS", "CSII", "FARR", "HSSS", "PODD"],
                // "txt_vrfy" => ["ACC", "BIAS", "CSI", "FAR", "HSS", "POD"],
                // "title_vrfy" => ["ACC", "BIAS", "CSI", "FAR", "HSS", "POD"]
                "txt_vrfy" => ["ACC", "FBI", "CSI", "FAR", "HSS", "POD"],
                "title_vrfy" => ["ACC", "FBI", "CSI", "FAR", "HSS", "POD"]
            ];
        } else if( $vn == "SKY" || $vn == "VEC" ) {
            $vrfyTech = [
                "data_vrfy" => ["HSSS", "PCC0", "PCC1"],
                "txt_vrfy" => ["HSS", "PC", "PC(1구간차)"],
                "title_vrfy" => ["HSS", "PC", "PC(1구간차)"]
            ];
        } else if( $vn == "POP" ) {
            $vrfyTech = [
                "data_vrfy" => ["BRS0", "BRS1", "BRSS"],
                "txt_vrfy" => ["무강수", "유강수", "all"],
                "title_vrfy" => ["무강수", "유강수", "all"]
            ];
        } else if( $vn == "RN3" ) {
            // $vt = ["BIS", "CSI", "ETS"];
            $vt = ["CSI", "BIS", "ETS"];
            $vt_arr = array();
            $txt = ["1mm", "10mm", "60mm", "90mm"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<5; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "BIAS";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt_arr,
                "title_vrfy" => $title_arr
            ];
        } else if( $vn == "RN1" ) {
            // $vt = ["BIS", "CSI", "ETS"];
            $vt = ["CSI", "BIS", "ETS"];
            $vt_arr = array();
            // $txt = ["1mm", "10mm", "60mm", "90mm"];
            // $txt = ["0.5mm", "5mm", "20mm", "50mm"];
            $txt = ["0.1mm", "0.5mm", "1mm", "10mm"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<5; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        // $vrfy_n = "BIAS";
                        $vrfy_n = "FBI";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt_arr,
                "title_vrfy" => $title_arr
            ];
        } else if( $vn == "RN6" || $vn == "R12" ) {
            // $vt = ["BIS", "CSI", "ETS"];
            $vt = ["CSI", "BIS", "ETS"];
            $vt_arr = array();
            $txt = ["1mm", "10mm", "70mm", "1100mm"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<5; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "BIAS";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt_arr,
                "title_vrfy" => $title_arr
            ];
        } else if( $vn == "SN3" || $vn == "SN6" || $vn == "S12" || $vn == "SN1" ) {
            // $vt = ["BIS", "CSI", "ETS"];
            $vt = ["CSI", "BIS", "ETS"];
            $vt_arr = array();
            $txt = ["0.1cm", "1cm", "5cm", "10cm"];
            $txt_arr = array();
            $title_arr = array();
            foreach ($vt as $arr) {
                for( $v=1; $v<5; $v++) {
                    array_push($vt_arr, $arr . $v);
                }
                    if( $arr == "BIS" ) {
                        $vrfy_n = "BIAS";
                    } else {
                        $vrfy_n = $arr;
                    }
                for( $t=0; $t<sizeof($txt); $t++) {
                    array_push($txt_arr, $txt[$t]);
                    array_push($title_arr, $vrfy_n . "(" . $txt[$t] . ")");
                }
            }
            $vrfyTech = [
                "data_vrfy" => $vt_arr,
                "txt_vrfy" => $txt_arr,
                "title_vrfy" => $title_arr
            ];
        }
        
        return $vrfyTech;
    }









}