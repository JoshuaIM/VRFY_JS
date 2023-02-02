<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tsgrdcommon_func {
    
    // UI에서 사용자가 선택한 XY값을 받아 격자 데이터의 위치(좌표) 계산하는 메서드. 
    public function calcXYCoordinate($xy_point, $nx, $ny) {

        $dataXY = array();
        
        foreach ($xy_point as $num) {
            
            $xy = explode(",", $num);
            $xx = (int)$xy[0];
            $yy = (int)$xy[1];
            
//             $dx = ( (($yy -1) * $ny) + $xx) % 10;   
//             //$dy = sprintf('%d',  ((($yy -1) * $ny) + $xx) / 10 );   
//             $dy = ceil( ((($yy -1) * $ny) + $xx) / 10 );
            
            $dx = ( ($yy-1) * $nx + $xx ) % 10;
            $dy = ceil( ( ($yy-1) * $nx + $xx ) / 10);
            
            $xy_p = [
                'x_point' => $dx,
                //'y_point' => (int)$dy
                'y_point' => $dy,
                // 지도상의 XY좌표값
                'pointInfo' => $xx . "X" . $yy,
                // 지도상의 XY좌표값 타이틀에 표출
                'pointTitle' => $xx . "," . $yy,
                // 데이터상의 XY좌표값
                'dataPointInfo' => $dx . "X" . $dy
            ];            
            
            array_push($dataXY, $xy_p);
        }
        
        return $dataXY;        
    }

    
    
//////////////////////////////////////////////////////////////////////////////
/////   예측기간(월별) 단기*중기 모두 사용
//////////////////////////////////////////////////////////////////////////////
    
    // View의 선택 값을 조합하여 파일명 생성 후
    // 데이터 추출하는 메서드.
    public function getFcstData($fnParam) {
        $dataArr = array();
        
        // 순서: 검증지수 - 지점  - 월 - UTC - 모델
        foreach ($fnParam['vrfy_idx'] as $vrfy) {
            foreach ($fnParam['rangeMon'] as $mon) {
                foreach ($fnParam['model_sel'] as $modl) {
                    // TODO: 2019-09-26 제공받은 데이터는 YYYYMM 상위 디렉터리인 모델 디렉터리가 없어졌다. 그러므로 사용안함.
                    //$modlTechArr = explode("_", $modl);
                    //$modlOnly = $modlTechArr[0];
                    $ymDir = $mon['ymInfo'];
                    
                    //$modl_ym_dir = $modlOnly . "/" . $ymDir . "/";
                    // TODO: 2019-09-26 제공받은 데이터는 YYYYMM 상위 디렉터리인 모델 디렉터리가 없어졌다. 그러므로 사용안함.
                    $modl_ym_dir = "/" . $ymDir . "/";
                    
                    // 파일명 조합.
                    $tmpfn = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['data'];

//                     // 지점자료(STN)의 예보시간이 적힌 헤드를 읽어 사용하기 위함.
//                     $stnfn = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/" . $fnParam['stn_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['data'];
                    
                    // 파일이 존재할 경우
                    if( file_exists($tmpfn) ) {
//                     if( file_exists($tmpfn) && file_exists($stnfn) ) {
                        
//                         // 데이터 파일 헤더의 UTC 정보 제공. ( 그래프 X축의 forecast 시간 표출용 )
//                         $stnLine = explode("\n", file_get_contents($stnfn));
//                         $fHeadUtc = $this->getFileHeadUtc($stnLine[1]);
                        
                        // 줄 단위 파일 읽기.
                        $fileLine = explode("\n", file_get_contents($tmpfn));
                            $tmp_fnum = sizeof($fileLine)-1;

                            for( $i=$tmp_fnum; $i>$tmp_fnum-3; $i--) {
                                // 격자 데이터의 마지막 줄 확인하여 빈칸이면 삭제.
                                if( $fileLine[$i] == null ) {
                                    array_pop($fileLine);
                                }
                            }
                        // 최종 격자 데이터 줄 수.
                        $f_num = sizeof($fileLine);
                        
                        //array_push($dataArr, $f_num);
                        
                        // 격자: x축(149) * y축(253) 이 데이터에 10줄씩 쓰여졌으므로 /10, 소수점 자리수가 x축의 순서(열)를 나타내고 올림을 한 수가 y축의 순서(행)을 나타낸다.
                        // 또한 예측시간을 나타내는 헤더가 있으므로 +1을 해준다.
                        // 위 값으로 다음 예보시간의 행, 열을 구하여 값을 찾을 수 있다.
                        $fc_line = ceil( ($fnParam['nx'] * $fnParam['ny']) / 10 ) +1;

                        // 예보시간의 개수 (AVE 포함: 맨 끝은 AVE라고 가정.)
                        $fcast_num = $f_num / $fc_line;                        
                        // TODO: 정수가 나오지 않으면 Error 이다. 예외처리 필요.
                        
                        // 예보시간이 적힌 헤더들을 찾아서 표출에 맞는 문자로 가공.
                        $fHeadUtc = array();
                            // AVE는 읽기 않음. 마지막에 'AVE' String 추가만 필요.
                            for( $h=0; $h<$fcast_num-1; $h++) {
                                $h_l = $h * $fc_line;
    
                                $fcast_h = $this->getHeadUtc($fileLine[$h_l]);
                                
                                array_push($fHeadUtc, $fcast_h);
                            }
                            array_push($fHeadUtc, "AVE");

                        $xy_data = array();
                        foreach ($fnParam['xyArr'] as $xy) {

                            $y_dline = $xy['y_point'];
                            
                            $data = array();
                            for( $fc=0; $fc<sizeof($fHeadUtc); $fc++ ) {
                                
                                $d_row = $y_dline + ($fc * $fc_line);
                                
                                $find_line = $fileLine[$d_row];
                                
                                $find_data = $this->getTargData($find_line, $xy['x_point']);
                                
                                if($find_data == "-999.0") {
                                    array_push($data, null );
                                } else {
                                    array_push($data, floatval( $find_data ) );
                                }
                                
                            }
                            
                            $xyD= [
                                'xy_info' => $xy,
                                'xy_data' => $data
                            ];
                            array_push($xy_data, $xyD);
                        }
                        
                        // data의 정보.
                        $dinfo = $vrfy . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                            
                            $dArray = [
                                // 파일명 검사용.
                                'fileName' => $tmpfn,
                                //'dateInfo' => $fHeadDate,
                                'fHeadUtc' => $fHeadUtc,
                                'dataInfo' => $dinfo,
                                'data' => $xy_data
                            ];
                            array_push($dataArr, $dArray);

                    } // End of "File exist" if 문.
                } // End of "MODEL" foreach.
            } // End of "RANGEMON" foreach.
        } // End of "VRFY IDX" foreach.
        
        return $dataArr;
    }
    
    
    
// 격자 파일 헤더의 UTC 정보 가공.
    public function getHeadUtc($fcLine) {
        
        $substr = substr($fcLine, -7);
        $substr = (int)substr($substr, 0, -4);
        $substr = "+" . $substr . "H";
        
        return $substr;
    }
    
    
// 해당 라인의 x-point 값을 받아 찾아내는 메서드.
    public function getTargData($data_line, $x_point) {
        
        $d = trim($data_line);
        
        // whitespace(연속)를 '#'으로 변경한다.
        $rep_whitesp = trim( preg_replace('/\s+/', '#', $d) );
        // '#'로 잘라서 배열에 넣는다.
        $data = explode("#", $rep_whitesp);

        ($x_point == 0) ? $idx = 10 : $idx = $x_point;
        
        $targData = $data[$idx-1];
        
        return $targData;
    }
    
    

    public function arrangeFcstData($data, $param) {
        
        $resData = array();

        for ( $xy=0; $xy<sizeof($data[0]['data']); $xy++ ) {
            
            $modl_mon_utc_arr = array();
            for ( $dnum=0; $dnum<sizeof($data); $dnum++ ) {
                $modl_mon_utc_data = [
                    "dataInfo" => $data[$dnum]['dataInfo'],
                    "fileName" => $data[$dnum]['fileName'],
                    "point_info" => $data[$dnum]['data'][$xy]['xy_info']['pointInfo'],                    
                    "point_title" => $data[$dnum]['data'][$xy]['xy_info']['pointTitle'],                    
                    "dataPoint_info" => $data[$dnum]['data'][$xy]['xy_info']['dataPointInfo'],                    
                    "data" => $data[$dnum]['data'][$xy]['xy_data']
                ];                
                array_push($modl_mon_utc_arr, $modl_mon_utc_data);
            }
            
            $xy_data = [
                "data_info" => $param['var_select'] . "_" . $param['vrfy_idx'][0],
                "point_info" => $data[0]['data'][$xy]['xy_info']['pointInfo'],                    
                "point_title" => $data[0]['data'][$xy]['xy_info']['pointTitle'],                    
                "dataPoint_info" => $data[0]['data'][$xy]['xy_info']['dataPointInfo'],                    
                "fHeadUtc" => $data[0]['fHeadUtc'],
                "xyData" => $modl_mon_utc_arr
            ];
            array_push($resData, $xy_data);
            
            
        }
            
//         foreach ($param['vrfy_idx'] as $vf) {
//             foreach ($param['location'] as $lc) {
                
//                 $mon_utc_modl = array();
//                 foreach ($param['rangeMon'] as $mon) {
//                     foreach ($param['model_sel'] as $modl) {
                        
//                         $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                        
//                         // TODO: $tmp(search결과 array key값)이 없을 경우와 1번째 배열의 결과값 0 둘다 if문에서는 false 이다... PHP잘못된 디자인의 프랙탈.
//                         // 그러므로 in_array를 사용하여 배열값을 확인하고 -> 있으면 결과값이 1이므로. array_search를 사용한다.
//                         $dExist = in_array( $dn, array_column($data, 'dataInfo') );
//                         if($dExist) {
//                             $tmp = array_search( $dn, array_column($data, 'dataInfo') );
//                             $dataArray = [
//                                 // 파일명 검사용.
//                                 'search' => $dExist . " || ",
//                                 'fileName' => $data[$tmp]['fileName'],
//                                 'fHeadUtc' => $data[$tmp]['fHeadUtc'],
//                                 'fDataNum' => $data[$tmp]['fDataNum'],
//                                 'vrfy_loc' => $vf . "_" . $lc,
//                                 'month' => substr($mon['data'], 0, 6),
//                                 //'ym_range' => $mon['ymRange'],
//                                 'utc' => $mon['utcInfo'],
//                                 'model' => $modl,
//                                 'data' => $data[$tmp]['data']
//                             ];
                            
//                             array_push($mon_utc_modl, $dataArray);
//                         } // End of "tmp(search결과값)" if문
//                     } // End of "model_sel" foreach.
//                 } // End of "rangeMon" foreach.
                
//                 $vrfy_loc = [
//                     'var_name' => $param['var_select'],
//                     'vrfy_loc' => $vf . "_" . $lc,
//                     'data' => $mon_utc_modl
//                 ];
                
//                 array_push($resData, $vrfy_loc);
//             }
//         }
        
        return $resData;
    }
    
    
    
    
//////////////////////////////////////////////////////////////////////////////
/////   임의기간 단기*중기 모두 사용
//////////////////////////////////////////////////////////////////////////////
    
// View의 선택 값을 조합하여 파일명 생성 후
// 데이터 추출하는 메서드.
    public function getArbiData($fnParam) {
        $dataArr = array();
        
        // 순서: 검증지수 - 지점  - 월 - UTC - 모델
        foreach ($fnParam['vrfy_idx'] as $vrfy) {
            foreach ($fnParam['rangeMon'] as $mon) {
                foreach ($fnParam['model_sel'] as $modl) {
                    
                    // 파일명 조합.
                    $tmpfn = $fnParam['dir_name'] . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['range_mon'];
                    
                    // 파일이 존재할 경우
                    if( file_exists($tmpfn) ) {
                        
                        // 줄 단위 파일 읽기.
                        $fileLine = explode("\n", file_get_contents($tmpfn));
                        $tmp_fnum = sizeof($fileLine)-1;
                        
                        for( $i=$tmp_fnum; $i>$tmp_fnum-3; $i--) {
                            // 격자 데이터의 마지막 줄 확인하여 빈칸이면 삭제.
                            if( $fileLine[$i] == null ) {
                                array_pop($fileLine);
                            }
                        }
                        // 최종 격자 데이터 줄 수.
                        $f_num = sizeof($fileLine);
                        
                        //array_push($dataArr, $f_num);
                        
                        // 격자: x축(149) * y축(253) 이 데이터에 10줄씩 쓰여졌으므로 /10, 소수점 자리수가 x축의 순서(열)를 나타내고 올림을 한 수가 y축의 순서(행)을 나타낸다.
                        // 또한 예측시간을 나타내는 헤더가 있으므로 +1을 해준다.
                        // 위 값으로 다음 예보시간의 행, 열을 구하여 값을 찾을 수 있다.
                        $fc_line = ceil( ($fnParam['nx'] * $fnParam['ny']) / 10 ) +1;
                        
                        // 예보시간의 개수 (AVE 포함: 맨 끝은 AVE라고 가정.)
                        $fcast_num = $f_num / $fc_line;
                        // TODO: 정수가 나오지 않으면 Error 이다. 예외처리 필요.
                        
                        // 예보시간이 적힌 헤더들을 찾아서 표출에 맞는 문자로 가공.
                        $fHeadUtc = array();
                        // AVE는 읽기 않음. 마지막에 'AVE' String 추가만 필요.
                        for( $h=0; $h<$fcast_num-1; $h++) {
                            $h_l = $h * $fc_line;
                            
                            $fcast_h = $this->getHeadUtc($fileLine[$h_l]);
                            
                            array_push($fHeadUtc, $fcast_h);
                        }
                        array_push($fHeadUtc, "AVE");
                        
                        $xy_data = array();
                        foreach ($fnParam['xyArr'] as $xy) {
                            
                            $y_dline = $xy['y_point'];
                            
                            $data = array();
                            for( $fc=0; $fc<sizeof($fHeadUtc); $fc++ ) {
                                
                                $d_row = $y_dline + ($fc * $fc_line);
                                
                                $find_line = $fileLine[$d_row];
                                
                                $find_data = $this->getTargData($find_line, $xy['x_point']);
                                
                                if($find_data == "-999.0") {
                                    array_push($data, null );
                                } else {
                                    array_push($data, floatval( $find_data ) );
                                }
                                
                            }
                            
                            $xyD= [
                                'xy_info' => $xy,
                                'xy_data' => $data
                            ];
                            array_push($xy_data, $xyD);
                        }
                        
                        // data의 정보.
                        $dinfo = $vrfy . "_" . $mon['utc_info'] . "_" . $modl;
                        
                        $dArray = [
                            // 파일명 검사용.
                            'fileName' => $tmpfn,
                            //'dateInfo' => $fHeadDate,
                            'fHeadUtc' => $fHeadUtc,
                            'dataInfo' => $dinfo,
                            'data' => $xy_data
                        ];
                        array_push($dataArr, $dArray);
                        
                    } // End of "File exist" if 문.
                } // End of "MODEL" foreach.
            } // End of "RANGEMON" foreach.
        } // End of "VRFY IDX" foreach.
        
        return $dataArr;
    }
    
    
    
    
    public function arrangeArbiData($data, $param) {
        
        $resData = array();
        
        if( $data ) {
            for ( $xy=0; $xy<sizeof($data[0]['data']); $xy++ ) {
                
                $modl_mon_utc_arr = array();
                for ( $dnum=0; $dnum<sizeof($data); $dnum++ ) {
                    $modl_mon_utc_data = [
                        "dataInfo" => $data[$dnum]['dataInfo'],
                        "fileName" => $data[$dnum]['fileName'],
                        "point_info" => $data[$dnum]['data'][$xy]['xy_info']['pointInfo'],
                        "point_title" => $data[$dnum]['data'][$xy]['xy_info']['pointTitle'],
                        "dataPoint_info" => $data[$dnum]['data'][$xy]['xy_info']['dataPointInfo'],
                        "data" => $data[$dnum]['data'][$xy]['xy_data']
                    ];
                    array_push($modl_mon_utc_arr, $modl_mon_utc_data);
                }
                
                $xy_data = [
                    "data_info" => $param['var_select'] . "_" . $param['vrfy_idx'][0],
                    "point_info" => $data[0]['data'][$xy]['xy_info']['pointInfo'],
                    "point_title" => $data[0]['data'][$xy]['xy_info']['pointTitle'],
                    'rangeInfo' => $param['rangeInfo'],
                    "dataPoint_info" => $data[0]['data'][$xy]['xy_info']['dataPointInfo'],
                    "fHeadUtc" => $data[0]['fHeadUtc'],
                    "xyData" => $modl_mon_utc_arr
                ];
                array_push($resData, $xy_data);
            }
        }
        
        return $resData;
    }
    
    
    
    
    
    
    
}
