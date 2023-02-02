<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tstbcommon_func {
    
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
                    
                    // 파일이 존재할 경우
                    if( file_exists($tmpfn) ) {
                        
                        // 줄 단위 파일 읽기.
                        $fileLine = explode("\n", file_get_contents($tmpfn));
                        
                        // TODO: 지금 그래픽 표출상 적용하지는 못할듯 하다.
                        // 파일 헤드(첫번째와 두번째로 날짜 계산 - 시계열 X축 날짜 대입을 위함.)로 날짜 계산.
                        //$fHeadDate = $this->get_file_head_date($fileLine[0], $fileLine[1]);
                        
                        // 데이터 파일 헤더의 UTC 정보 제공. ( 그래프 X축의 forecast 시간 표출용 )
                        $fHeadUtc = $this->getFileHeadUtc($fileLine[1]);
                        
                        // 데이터 파일 자료수 정보 제공. ( 집계표에서 사용. )
                        $fDataNum = "";
                        // 자료수 정보는 파일 마지막에 있으므로 배열을 거꾸로 돌려서 찾고 찾으면 foreach 탈출.
                        foreach ( array_reverse($fileLine) as $revLine) {
                            $locId = trim(substr($revLine, 0, 5));
                            if( $locId == "NUM" ) {
                                $fDataNum = $this->getFileDataNum($revLine);                                
                                break;
                            }
                        }
                        
                        // 파일별 지점 찾아 해당 데이터 얻기.
                        foreach ($fileLine as $line) {
                            // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
                            $loc = trim(substr($line, 0, 5));
                            foreach ($fnParam['location'] as $mat) {
                                if( $loc == $mat ) {
                                    // data의 정보.
                                    $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                    $mkArr = $this->splitFcstData($line);
                                    
                                    $dArray = [
                                        // 파일명 검사용.
                                        'fileName' => $tmpfn,
                                        //'dateInfo' => $fHeadDate,
                                        'fHeadUtc' => $fHeadUtc,
                                        'fDataNum' => $fDataNum,
                                        'dataInfo' => $dinfo,
                                        'data' => $mkArr
                                    ];
                                    array_push($dataArr, $dArray);
                                }
                            } // End of "Location" foreach.
                        } // End of "File Line" foreach.
                    } // End of "File exist" if 문.
                    else {
                        array_push($dataArr, $tmpfn);
                    }
                } // End of "MODEL" foreach.
            } // End of "RANGEMON" foreach.
        } // End of "VRFY IDX" foreach.
        
        return $dataArr;
    }


// 데이터 파일 헤더의 UTC 정보 제공.
    public function getFileHeadUtc($hourLine) {
        $utcInfo = array();

// 2019-11-26 최저기온의 경우 예측시간 헤더에 이상한 문자가 포함되는 값이 있어 공백으로 자르는 방법으로 교체.
//         $rep_whitesp = preg_replace('/[H]/', ' ', $hourLine);
//         $hourStr = explode("+", $rep_whitesp);
        
//         // 첫번째 배열은 'STNID'이므로 필요 없음.
//         // +뒤에 바로 숫자로 나오게 하기 위해 int로 바꿨다가 다시 string으로 변환하면서 필요한 문자 삽입.
//         for( $h=1; $h<sizeof($hourStr); $h++ ) {
//             $tmpH = (int)$hourStr[$h];
//             $resH = "+" . strval($tmpH) . "H";
            
//             array_push($utcInfo, $resH);
//         }
// 2019-11-26 최저기온의 경우 예측시간 헤더에 이상한 문자가 포함되는 값이 있어 공백으로 자르는 방법으로 교체.

        $rep_whitesp = trim( preg_replace('/\s+/', '#', $hourLine) );
        
        $hourStr = explode("#", $rep_whitesp);
        
// 2019-11-27 최저기온의 경우 예측시간 헤더에 이상한 문자가 "-003H" 로 표출된다고 하므로... '-3H'로 표출되도록 다시 변경
//         // 첫번째 배열은 'STNID  '이므로 필요 없음.
//         for( $h=1; $h<sizeof($hourStr); $h++ ) {
//             // 앞의 '+' 또는 '-' 문자와 맨뒤의 'H' 제외.
//             $n = substr( $hourStr[$h], 1, 3 );
//             if( is_numeric($n) ) {
//                 $tmpH = (int)$n;
//                 $resH = "+" . strval($tmpH) . "H";
//             } else {
//                 $resH = "-000H";
//             }
            
//             array_push($utcInfo, $resH);
//         }
// 2019-11-27 최저기온의 경우 예측시간 헤더에 이상한 문자가 "-003H" 로 표출된다고 하므로... '-3H'로 표출되도록 다시 변경

        // 첫번째 배열은 'STNID  '이므로 필요 없음.
        for( $h=1; $h<sizeof($hourStr); $h++ ) {
            // 앞의 기호 값 저장.
            $comp = substr( $hourStr[$h], 0, 1 );
            // 앞의 '+' 또는 '-' 문자와 맨뒤의 'H' 제외.
            $n = substr( $hourStr[$h], 1, 3 );
            // 예측시간 헤더가 숫자이면 int로 변화하여 표출하고 아니면 그대로 표출.
            if( is_numeric($n) ) {
                $tmpH = (int)$n;
                $resH = $comp . strval($tmpH) . "H";
            } else {
                $resH = $comp . $n . "H";
            }
            
            array_push($utcInfo, $resH);
        }
        
        return $utcInfo;
    }
    
    
    
// 예측기간(월별)에서 사용.
// 데이터파일에서 읽어들인 1줄의 data를 7칸씩 잘라서 array로 만들어 반환하는 메서드.
// STNID(첫번째 시작)은 5칸을 잘라야 한다.
    //public function splitFcstData($dataLine, $mapType) {
    public function splitFcstData($dataLine) {
        
        $resData = array();
        
        // STNID는 5칸이므로 데이터에서 0-5칸 제외시킨다.
        $stnidNum = 5;
        $splitStnid = substr($dataLine, $stnidNum, strlen($dataLine));
        
        // whitespace(연속)를 '#'으로 변경한다.
        $rep_whitesp = trim( preg_replace('/\s+/', '#', $splitStnid) );
        // '#'로 잘라서 배열에 넣는다.
        $data = explode("#", $rep_whitesp);
        
        $loopNum = sizeof($data);
            // 시계열의 경우 데이터 맨 끝 AVE값은 필요 없다.
            //if($mapType == "ts") {
            //    $loopNum = $loopNum -1;                
            //}
        
        // 첫번쨰는 빈칸이므로(STNID-첫번째값 사이 빈칸) 1부터 시작.
        for( $d=1; $d<$loopNum; $d++) {
            if($data[$d] == "-999.0") {
                array_push($resData, null );
            } else {
                array_push($resData, floatval( $data[$d]) );
            }
        }
        
        return $resData;
    }
    
    
    
    public function getFileDataNum($numLine) {
        // whitespace(연속)를 '#'으로 변경한다.
        $rep_whitesp = trim( preg_replace('/\s+/', '#', $numLine) );
        $numLineArr = explode("#", $rep_whitesp);
        $numLineSize = sizeof($numLineArr);
        
        return $numLineArr[$numLineSize-1];
    }
    
    
    
    public function arrangeFcstData($data, $param) {
        
        $resData = array();
        foreach ($param['vrfy_idx'] as $vf) {
            foreach ($param['location'] as $lc) {
                
                $mon_utc_modl = array();
                foreach ($param['rangeMon'] as $mon) {

                    $modl_size_num = sizeof($param['model_sel']);
                    foreach ($param['model_sel'] as $modl) {
                        
                        $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                        
                        // TODO: $tmp(search결과 array key값)이 없을 경우와 1번째 배열의 결과값 0 둘다 if문에서는 false 이다... PHP잘못된 디자인의 프랙탈.
                        // 그러므로 in_array를 사용하여 배열값을 확인하고 -> 있으면 결과값이 1이므로. array_search를 사용한다.
                        $dExist = in_array( $dn, array_column($data, 'dataInfo') );
                        if($dExist) {
                            $tmp = array_search( $dn, array_column($data, 'dataInfo') );
                            
                            // TODO : 2022-12-02 ECMWF 예측기간(+150h)을 나머지 기간(+135h) 로 맞춰주는 작업.
                            $data_arr = array();
                            $fHeadUtc = array();
                            // if( $modl == "ECMW_NPPM" && $modl_size_num > 1 ) {
                            //     $data_arr = $this->suitForecastFormat($data[$tmp]['data'], "data");
                            //     $fHeadUtc = $this->suitForecastFormat($data[$tmp]['fHeadUtc'], "head");
                            // } else {
                                $data_arr = $data[$tmp]['data'];
                                $fHeadUtc = $data[$tmp]['fHeadUtc'];
                            // }

                            $dataArray = [
                                // 파일명 검사용.
                                'search' => $dExist . " || ",
                                'fileName' => $data[$tmp]['fileName'],
                                'fHeadUtc' => $fHeadUtc,
                                'fDataNum' => $data[$tmp]['fDataNum'],
                                'vrfy_loc' => $vf . "_" . $lc,
                                'month' => substr($mon['data'], 0, 6),
                                //'ym_range' => $mon['ymRange'],
                                'utc' => $mon['utcInfo'],
                                'model' => $modl,
                                'num' => $modl_size_num,
                                'data' => $data_arr
                            ];
                            
                            array_push($mon_utc_modl, $dataArray);
                        } // End of "tmp(search결과값)" if문
                    } // End of "model_sel" foreach.
                } // End of "rangeMon" foreach.
                
                $vrfy_loc = [
                    'var_name' => $param['var_select'],
                    'vrfy_loc' => $vf . "_" . $lc,
                    'data' => $mon_utc_modl
                ];
                
                array_push($resData, $vrfy_loc);
            }
        }
        
        return $resData;
    }

    

//////////////////////////////////////////////////////////////////////////////
/////   월별  단기*중기 모두 사용
//////////////////////////////////////////////////////////////////////////////
    
// View의 선택 값을 조합하여 파일명 생성 후
// 데이터 추출하는 메서드.
    public function getMonData($fnParam) {
        $dataArr = array();
        
        // 순서: 검증지수 - 지점(추가해야함) - 월 - UTC - 모델
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
                    
                    // 파일이 존재할 경우
                    if( file_exists($tmpfn) ) {
                        
                        // 줄 단위 파일 읽기.
                        $fileLine = explode("\n", file_get_contents($tmpfn));
                        
                        // 파일별 지점 찾아 해당 데이터 얻기.
                        foreach ($fileLine as $line) {
                            // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
                            $loc = trim(substr($line, 0, 5));
                            
                            foreach ($fnParam['location'] as $mat) {
                                if( $loc == $mat ) {
                                    // data의 정보.
                                    $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                    $mkArr = $this->splitMonthData($line);
                                    
                                    $dArray = [
                                        // 파일명 검사용.
                                        'monthInfo' => $mon['ymInfo'],
                                        'utcInfo' => $mon['utcInfo'],
                                        'fileName' => $tmpfn,
                                        'dataInfo' => $dinfo,
                                        'data' => $mkArr
                                    ];
                                    array_push($dataArr, $dArray);
                                }
                            } // End of "Location" foreach.
                        } // End of "File Line" foreach.
                    } // End of "MONTH" foreach.
                } // End of "File exist" if 문.
            } // End of "MODEL" foreach.
        } // End of "VRFY IDX" foreach.
        
        return $dataArr;
    }
    
    
    
// 월별에서 사용.
// 데이터파일에서 읽어들인 1줄의 data 맨끝값(AVE)을 반환하는 메서드.
// STNID(첫번째 시작)은 5칸을 잘라야 한다.
    public function splitMonthData($dataLine) {
        
        $resData;
        
        // STNID는 5칸이므로 데이터에서 0-5칸 제외시킨다.
        $stnidNum = 5;
        $splitStnid = substr($dataLine, $stnidNum, strlen($dataLine));
        
        // whitespace(연속)를 '#'으로 변경한다.
        $rep_whitesp = trim( preg_replace('/\s+/', '#', $splitStnid) );
        // '#'로 잘라서 배열에 넣는다.
        $data = explode("#", $rep_whitesp);
        
        $loopNum = sizeof($data);

        if($data[$loopNum-1] == "-999.0") {
            $resData = null;
        } else {
            $resData = floatval( $data[$loopNum-1] );
        }
        
        return $resData;
    }
    
    
    
    public function arrangeMonData($data, $param) {
        
        $resData = array();
        foreach ($param['vrfy_idx'] as $vf) {
            foreach ($param['location'] as $lc) {
                
                $mon_utc_modl = array();
                foreach ($param['model_sel'] as $modl) {

                    // TODO: 2019-09-23 초기시각 멀티셀렉션으로 바뀌면서 소스 수정함. ( 초기시각 값이 단일 값으로 오던게 -> array 로 넘어옴. )
                    for($i=0; $i<sizeof($param['init_hour']); $i++) {
    
                        $utc_val = $param['init_hour'][$i];
                            $targUTC = explode("#" , $utc_val);
                            $strtUTC = $targUTC[0];
                            $endUTC = $targUTC[1];
                        $utc_comp = $strtUTC . "UTC";
                            if( $strtUTC != $endUTC ) {
                                $utc_comp = $strtUTC . "-" . $endUTC . "UTC";
                            }
                        
                        // 모아둔 month 데이터의 . (UTC는 별도)
                        $mon_range= array();
                        // month 데이터를 합친 배열. (UTC는 별도)
                        $mon_integ = array();
                        $utc_info = "";
                        foreach ($param['rangeMon'] as $mon) {
                            
                            if ( $utc_comp == $mon['utcInfo'] ) {
                                
                                $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                
                                // TODO: $tmp(search결과 array key값)이 없을 경우와 1번째 배열의 결과값 0 둘다 if문에서는 false 이다... PHP잘못된 디자인의 프랙탈.
                                // 그러므로 in_array를 사용하여 배열값을 확인하고 -> 있으면 결과값이 1이므로. array_search를 사용한다.
                                $dExist = in_array( $dn, array_column($data, 'dataInfo') );
                                
                                if($dExist) {
                                    $tmp = array_search( $dn, array_column($data, 'dataInfo') );
                                    array_push($mon_integ, $data[$tmp]['data']);
                                } else {
                                    array_push($mon_integ, null);
                                }
                                // End of if문
                                
                                $utc_info = $mon['utcInfo'];
                                array_push( $mon_range, substr($mon['data'], 0, 6) );
                            }
                        }
                        // End of foreach 'rangeMon'.
                        
                        $dataArr = [
                            'model' => $modl,
                            'utcInfo' => $utc_info,
                            'mon_range' => $mon_range,
                            'data' => $mon_integ
                        ];
                        
                        array_push( $mon_utc_modl, $dataArr);                                
                    }
                            
                } // End of "model_sel" foreach.

                
                $vrfy_loc = [
                    'var_name' => $param['var_select'],
                    'vrfy_loc' => $vf . "_" . $lc,
                    'data' => $mon_utc_modl
                ];
                
                array_push($resData, $vrfy_loc);
            }
        }
        
        return $resData;
    }
    
    
    
//////////////////////////////////////////////////////////////////////////////
/////   임의기간 발표*발효, 단기*중기 모두 사용
//////////////////////////////////////////////////////////////////////////////

// 지점: 단기-중기 시계열 표출 시 사용되는 메서드.
// UI로부터 파라메터를 받아서 데이터 생산 쉘을 호출 후 데이터를 추출하는 메서드.
    public function getArbiData($fnParam) {
        $dataArr = array();
        
        // 순서: 검증지수 - 지점  - UTC - 모델
        foreach ($fnParam['vrfy_idx'] as $vrfy) {
            foreach ($fnParam['rangeMon'] as $mon) {
                foreach ($fnParam['model_sel'] as $modl) {
                    
//                    if( $fnParam['peri_type'] == "TARGET" ) {
                        // 파일명 조합.
//                        $tmpfn = $fnParam['dir_name'] . $fnParam['data_head'] . $fnParam['peri_type'] . "_" . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['range_mon'];
//                    } else {
                        // 파일명 조합.
//                        $tmpfn = $fnParam['dir_name'] . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['range_mon'];
//                    }
                        $tmpfn = $fnParam['dir_name'] . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['range_mon'];
                    
                    // 파일이 존재할 경우
                    if( file_exists($tmpfn) ) {
                        
                        // 줄 단위 파일 읽기.
                        $fileLine = explode("\n", file_get_contents($tmpfn));
                        
                        // 데이터 파일 헤더의 UTC 정보 제공. ( 그래프 X축의 forecast 시간 표출용 )
                        $fHeadUtc = $this->getArbiFileHeadUtc($fileLine[1], $fnParam['peri_type']);
                        
                        // 데이터 파일 자료수 정보 제공. ( 집계표에서 사용. )
                        $fDataNum = "";
                        // 자료수 정보는 파일 마지막에 있으므로 배열을 거꾸로 돌려서 찾고 찾으면 foreach 탈출.
                        foreach ( array_reverse($fileLine) as $revLine) {
                            $locId = trim(substr($revLine, 0, 5));
                            if( $locId == "NUM" ) {
                                $fDataNum = $this->getFileDataNum($revLine);
                                break;
                            }
                        }
                        
                        // 파일별 지점 찾아 해당 데이터 얻기.
                        foreach ($fileLine as $line) {
                            // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
                            $loc = trim(substr($line, 0, 5));
                            
                            foreach ($fnParam['location'] as $mat) {
                                if( $loc == $mat ) {
                                    // data의 정보.
                                    //$dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                    $dinfo = $vrfy . "_" . $loc . "_" . $mon['utc_info'] . "_" . $modl;
                                    $mkArr = $this->splitFcstData($line);
                                    
                                    $dArray = [
                                        // 파일명 검사용.
                                        'fileName' => $tmpfn,
                                        'fHeadUtc' => $fHeadUtc,
                                        'fDataNum' => $fDataNum,
                                        'dataInfo' => $dinfo,
                                        'data' => $mkArr
                                    ];
                                    array_push($dataArr, $dArray);
                                }
                            } // End of "Location" foreach.
                        } // End of "File Line" foreach.
                    } // End of "File exist" if 문.
                } // End of "MODEL" foreach.
            } // End of "RANGEMON" foreach.
        } // End of "VRFY IDX" foreach.
        
        return $dataArr;
    }
    
    

// 임의기간의 경우 시작시간 또는 목표시간 데이터 파일 헤더의 UTC 정보 제공.
    public function getArbiFileHeadUtc($hourLine, $peri_type) {
        
        $utcInfo = array();
        
// 2019-11-26 최저기온의 경우 예측시간 헤더에 이상한 문자가 포함되는 값이 있어 공백으로 자르는 방법으로 교체.
//         $rep_whitesp = preg_replace('/[H]/', ' ', $hourLine);
        
//         if($peri_type == "TARG") {
//             $hourStr = explode("-", $rep_whitesp);
//         } else {
//             $hourStr = explode("+", $rep_whitesp);
//         }

//         // 첫번째 배열은 'STNID  '이므로 필요 없음.
//         // +뒤에 바로 숫자로 나오게 하기 위해 int로 바꿨다가 다시 string으로 변환하면서 필요한 문자 삽입.
//         for( $h=1; $h<sizeof($hourStr); $h++ ) {
//             $tmpH = (int)$hourStr[$h];
//                 if($peri_type == "TARG") {
//                     $resH = "-" . strval($tmpH) . "H";
//                 } else {
//                     $resH = "+" . strval($tmpH) . "H";
//                 }
            
//             array_push($utcInfo, $resH);
//         }
// 2019-11-26 최저기온의 경우 예측시간 헤더에 이상한 문자가 포함되는 값이 있어 공백으로 자르는 방법으로 교체.
        
        
        $rep_whitesp = trim( preg_replace('/\s+/', '#', $hourLine) );
        
        $hourStr = explode("#", $rep_whitesp);

// 2019-11-27 최저기온의 경우 예측시간 헤더에 이상한 문자가 "-003H" 로 표출된다고 하므로... '-3H'로 표출되도록 다시 변경 (이상한 문자도 표출되도록 추가)
//         // 첫번째 배열은 'STNID  '이므로 필요 없음.
//         for( $h=1; $h<sizeof($hourStr); $h++ ) {
//             // 앞의 '+' 또는 '-' 문자와 맨뒤의 'H' 제외.
//             $n = substr( $hourStr[$h], 1, 3 );
//             if( is_numeric($n) ) {
//                 $tmpH = (int)$n;
//                 if($peri_type == "TARG") {
//                     $resH = "-" . strval($tmpH) . "H";
//                 } else {
//                     $resH = "+" . strval($tmpH) . "H";
//                 }
//             } else {
//                 $resH = "-000H";
//             }

//             array_push($utcInfo, $resH);
//         }
// 2019-11-27 최저기온의 경우 예측시간 헤더에 이상한 문자가 "-003H" 로 표출된다고 하므로... '-3H'로 표출되도록 다시 변경 (이상한 문자도 표출되도록 추가)
        
        // 첫번째 배열은 'STNID  '이므로 필요 없음.
        for( $h=1; $h<sizeof($hourStr); $h++ ) {
            // 앞의 기호 값 저장.
            $comp = substr( $hourStr[$h], 0, 1 );
            // 앞의 '+' 또는 '-' 문자와 맨뒤의 'H' 제외.
            $n = substr( $hourStr[$h], 1, 3 );
            // 예측시간 헤더가 숫자이면 int로 변화하여 표출하고 아니면 그대로 표출.
            if( is_numeric($n) ) {
                $tmpH = (int)$n;
                $resH = $comp . strval($tmpH) . "H";
            } else {
                $resH = $comp . $n . "H";
            }

            array_push($utcInfo, $resH);
        }
        
        return $utcInfo;
    }
    
      
        
// 지점: 단기-중기 시계열 표출 시 사용되는 메서드.
// 추출 된 데이터들을 표출에 맞는 형식으로 변환해주는 메서드.
    public function arrangeArbiData($data, $param) {
        
        $resData = array();
        foreach ($param['vrfy_idx'] as $vf) {
            foreach ($param['location'] as $lc) {
                
                $mon_utc_modl = array();
                foreach ($param['rangeMon'] as $mon) {
                    foreach ($param['model_sel'] as $modl) {
                        
                        $dn = $vf . "_" . $lc . "_" . $mon['utc_info'] . "_" . $modl;

                        // TODO: $tmp(search결과 array key값)이 없을 경우와 1번째 배열의 결과값 0 둘다 if문에서는 false 이다... PHP잘못된 디자인의 프랙탈.
                        // 그러므로 in_array를 사용하여 배열값을 확인하고 -> 있으면 결과값이 1이므로. array_search를 사용한다.
                        $dExist = in_array( $dn, array_column($data, 'dataInfo') );
                        if($dExist) {
                            $tmp = array_search( $dn, array_column($data, 'dataInfo') );
                            $dataArray = [
                                // 파일명 검사용.
                                'search' => $dExist . " || ",
                                'fileName' => $data[$tmp]['fileName'],
                                'fHeadUtc' => $data[$tmp]['fHeadUtc'],
                                'fDataNum' => $data[$tmp]['fDataNum'],
                                'vrfy_loc' => $vf . "_" . $lc,
                                'utc' => $mon['utc_info'],
                                'model' => $modl,
                                'data' => $data[$tmp]['data']
                            ];
                            
                            array_push($mon_utc_modl, $dataArray);
                        } // End of "tmp(search결과값)" if문
                    } // End of "model_sel" foreach.
                } // End of "rangeMon" foreach.
                
                $vrfy_loc = [
                    'var_name' => $param['var_select'],
                    'vrfy_loc' => $vf . "_" . $lc,
                    'rangeInfo' => $param['rangeInfo'],
                    'data' => $mon_utc_modl
                ];
                
                array_push($resData, $vrfy_loc);
            }
        }
        
        return $resData;
    }
    

    // TODO : 2022-12-02 ECMWF 예측기간(+150h)을 나머지 기간(+135h) 로 맞춰주는 작업.
    public function suitForecastFormat($data, $type) {
        $sp_data = $data;
        $size = sizeof($sp_data);
        $res = array_splice($sp_data, 0, 130);
        if( $type == "data"){
            array_push($res, $data[$size-1]);
        }

        return $res;
    }


    
// 임의기간 표출 시 새로운 방법으로 데이터를 추출 하려고 했으나 기존 방식, 기존 소스를 활용하기로 하여 사용 안함.
// 추구하던 방식은 생산 쉘로부터 받은 생산 된 데이터 이름으로 데이터를 읽어 표출하도록 하려고 했다.
//     public function getArbiStnData($dataName, $dirName, $location) {
        
//         $loc_dataArr = array();
        
//         $dataFullName = $dirName . $dataName;
        
//         if( file_exists($dataFullName) ) {
            
//             // 줄 단위 파일 읽기.
//             $fileLine = explode("\n", file_get_contents($dataFullName));
            
//             // 데이터 파일 헤더의 UTC 정보 제공. ( 그래프 X축의 forecast 시간 표출용 )
//             $fHeadUtc = $this->getFileHeadUtc($fileLine[1]);
            
//             // 데이터 파일 자료수 정보 제공. ( 집계표에서 사용. )
//             $fDataNum = "";
//             // 자료수 정보는 파일 마지막에 있으므로 배열을 거꾸로 돌려서 찾고 찾으면 foreach 탈출.
//             foreach ( array_reverse($fileLine) as $revLine) {
//                 $locId = trim(substr($revLine, 0, 5));
//                 if( $locId == "NUM" ) {
//                     $fDataNum = $this->getFileDataNum($revLine);
//                     break;
//                 }
//             }

//             // 임의기간 실시간 생산 된 파일명으로부터 데이터 정보를 얻어낸다.(맵배열)
//             $dataInfo = $this->getInfoFromDataName($dataName);
            
//             // 파일별 지점 찾아 해당 데이터 얻기.
//             foreach ($fileLine as $line) {
                
//                 // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
//                 $loc = trim(substr($line, 0, 5));
                
//                 foreach ($location as $mat) {
//                     if( $loc == $mat ) {

//                         // 지점 매칭 된 데이터
//                         $mkArr = $this->splitFcstData($line);
                        
//                         //$dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
//                         $dinfo = $dataInfo['vrfy'] . "_" . $loc . "_" . $dataInfo['utc'] . "_" . $dataInfo['modl'];
                        
//                         $dArray = [
//                             'directory' => $dirName,
//                             'fileName' => $dataName,
//                             'dataInfo' => $dinfo,
//                             'infoArr' => $dataInfo,
//                             'data' => $mkArr
//                         ];
                        
//                         $loc_dataArr[$loc] = $dArray;
//                     }
//                 } // End of "Location" foreach.
//             } // End of "File Line" foreach.
            
//         } // End of if문 "if( file_exists($dataFullName) )"
        
        
//         return $loc_dataArr;
//     }
    
    
    
// // 임의기간 선택 시 데이터 이름으로부터 데이터의 정보를 맵배열로 만들어주는 메서드.
//     public function getInfoFromDataName($dataName){
        
//         $data_init_arr = explode(".", $dataName);
        
//         $varInfo_arr = explode("_", $data_init_arr[0]);
//         $initInfo_arr = explode("_", $data_init_arr[1]);

//         $utc_st = substr($initInfo_arr[0], -2);
//         $utc_ed = substr($initInfo_arr[1], -2);
        
//         if( $utc_st == $utc_ed ) {
//             $utc = $utc_ed . "UTC";
//         } else {
//             $utc = $utc_st . "-" . $utc_ed . "UTC";
//         }
        
//         $data_info = [
// //             'data_peri' => $varInfo_arr[2],
// //             'data_type' => $varInfo_arr[3],
// //             'modl' => $varInfo_arr[4] . "_" . $varInfo_arr[5],
// //             'var_name' => $varInfo_arr[6],
// //             'vrfy' => $varInfo_arr[8],
//             'data_peri' => $varInfo_arr[1],
//             'data_type' => $varInfo_arr[2],
//             'modl' => $varInfo_arr[3] . "_" . $varInfo_arr[4],
//             'var_name' => $varInfo_arr[5],
//             'vrfy' => $varInfo_arr[7],
//             'utc' => $utc
//         ];
        
        
//         return $data_info;
//     }
    
    
    


// 2023-01-09 지점선택-권역평균 표출을 위해 기존 로직 수정.
// View의 선택 값을 조합하여 파일명 생성 후
// 데이터 추출하는 메서드.
    public function getMeanFcstData($fnParam) {
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
                    
                    // 파일이 존재할 경우
                    if( file_exists($tmpfn) ) {
                        
                        // 줄 단위 파일 읽기.
                        $fileLine = explode("\n", file_get_contents($tmpfn));
                        
                        // TODO: 지금 그래픽 표출상 적용하지는 못할듯 하다.
                        // 파일 헤드(첫번째와 두번째로 날짜 계산 - 시계열 X축 날짜 대입을 위함.)로 날짜 계산.
                        //$fHeadDate = $this->get_file_head_date($fileLine[0], $fileLine[1]);
                        
                        // 데이터 파일 헤더의 UTC 정보 제공. ( 그래프 X축의 forecast 시간 표출용 )
                        $fHeadUtc = $this->getFileHeadUtc($fileLine[1]);
                        
                        // 데이터 파일 자료수 정보 제공. ( 집계표에서 사용. )
                        $fDataNum = "";
                        // 자료수 정보는 파일 마지막에 있으므로 배열을 거꾸로 돌려서 찾고 찾으면 foreach 탈출.
                        foreach ( array_reverse($fileLine) as $revLine) {
                            $locId = trim(substr($revLine, 0, 5));
                            if( $locId == "NUM" ) {
                                $fDataNum = $this->getFileDataNum($revLine);                                
                                break;
                            }
                        }
                        
                        
                        $location_data_arr = array();
                        $location_size = count($fnParam['location']);
                        // 파일별 지점 찾아 해당 데이터 얻기.
                        foreach ($fileLine as $line) {
                            // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
                            
                            $loc = trim(substr($line, 0, 5));
                            if($fnParam['location'][0] == "mean") {
                                
                                $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                $dArray = [
                                    // 파일명 검사용.
                                    'fileName' => $tmpfn,
                                    //'dateInfo' => $fHeadDate,
                                    'fHeadUtc' => $fHeadUtc,
                                    'fDataNum' => $fDataNum,
                                    'dataInfo' => $dinfo,
                                ];
                                
                                
                                for( $lo=0; $lo<$location_size-1; $lo++ ) {
                                    if( $loc == $fnParam['location'][$lo] ) {
                                        // data의 정보.
                                        $mkArr = $this->splitFcstData($line);
                                        array_push($location_data_arr, $mkArr);
                                        // array_push($dataArr, $mkArr);

                                        if( $lo == $location_size-2 ) {
                                            $dArray['data'] = $this->getMeanData($location_data_arr);
                                            $dArray['all_location_data'] = $location_data_arr;
                                            array_push($dataArr, $dArray);
                                        }
                                    }
                                }

                                


                                // $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                // $dArray = [
                                //      // 파일명 검사용.
                                //      'fileName' => $tmpfn,
                                //      'fHeadUtc' => $fHeadUtc,
                                //      'fDataNum' => $fDataNum,
                                // ];

                                // $temp_d = array();
                                // foreach ($fnParam['location'] as $mat) {
                                //     if( $loc == $mat ) {
                                //         // data의 정보.
                                //         $mkArr = $this->splitFcstData($line);
                                        
                                //         array_push($temp_d, $mkArr);
                                //     }

                                //     array_push($dataArr, $temp_d);
                                // } // End of "Location" foreach.



                                // $temp_data_arr = array();
                                // foreach ($fnParam['location'] as $mat) {
                                //     if( $loc == $mat ) {
                                //         // data의 정보.
                                //         // $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                //         $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                //         $mkArr = $this->splitFcstData($line);
                                        
                                //         $dArray = [
                                //             // 파일명 검사용.
                                //             'fileName' => $tmpfn,
                                //             //'dateInfo' => $fHeadDate,
                                //             'fHeadUtc' => $fHeadUtc,
                                //             'fDataNum' => $fDataNum,
                                //             'dataInfo' => $dinfo,
                                //             'data' => $mkArr
                                //         ];
                                //         // array_push($dataArr, $dArray);
                                //         // array_push($temp_data_arr, $dArray);
                                //         array_push($dataArr, $dArray['data']);
                                //     }
                                // } // End of "Location" foreach.





                            } else {
                                // foreach ($fnParam['location'] as $mat) {
                                //     if( $loc == $mat ) {
                                //         // data의 정보.
                                //         $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                //         $mkArr = $this->splitFcstData($line);
                                        
                                //         $dArray = [
                                //             // 파일명 검사용.
                                //             'fileName' => $tmpfn,
                                //             //'dateInfo' => $fHeadDate,
                                //             'fHeadUtc' => $fHeadUtc,
                                //             'fDataNum' => $fDataNum,
                                //             'dataInfo' => $dinfo,
                                //             'data' => $mkArr
                                //         ];
                                //         array_push($dataArr, $dArray);
                                //     }
                                // } // End of "Location" foreach.
                            }

                        } // End of "File Line" foreach.
                    } // End of "File exist" if 문.
                    else {
                        array_push($dataArr, $tmpfn);
                    }
                } // End of "MODEL" foreach.
            } // End of "RANGEMON" foreach.
        } // End of "VRFY IDX" foreach.
        
        return $dataArr;
    }


// 권역평균을 위해 만든 함수
    public function getMeanData($mean_data_arr) {

        // 한 파일의 모든 지역의 값 배열은 동일하다고 가정.
        $value_size = count($mean_data_arr[0]);
        // $location_size = count($mean_data_arr);

        $mean_arr = array();
        for( $i=0; $i<$value_size; $i++ ) {

            $location_value_arr = array();
            // $value_count = 0;
            foreach( $mean_data_arr as $loc ) {

                if( $loc[$i] != null ) {
                    array_push($location_value_arr, $loc[$i]);                
                }
            }

            // $mean_data = $this->getMeanRoundsData($location_value_arr, $value_count);
            $mean_data = $this->getMeanRoundsData($location_value_arr);

            array_push($mean_arr, $mean_data);
        }

        return $mean_arr;
    }


// 배열값 평균 후 반올림
    public function getMeanRoundsData($data_array) {

        // 배열의 총 데이터 개수
        $total_size = count($data_array);

        if( $total_size < 1 ) {
            $rounds = null;
        } else {
            // 평균
            $mean_data = array_sum($data_array)/$total_size;
            // 소수점 첫째자리 반올림
            $rounds = (double)number_format($mean_data, 1);
        }

        return $rounds;
    }



    public function arrangeMeanFcstData($data, $param) {
        
        $resData = array();
        foreach ($param['vrfy_idx'] as $vf) {
                
                $mon_utc_modl = array();
                foreach ($param['rangeMon'] as $mon) {

                    $modl_size_num = sizeof($param['model_sel']);
                    foreach ($param['model_sel'] as $modl) {
                        
                        $dn = $vf . "_mean_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                        
                        // TODO: $tmp(search결과 array key값)이 없을 경우와 1번째 배열의 결과값 0 둘다 if문에서는 false 이다... PHP잘못된 디자인의 프랙탈.
                        // 그러므로 in_array를 사용하여 배열값을 확인하고 -> 있으면 결과값이 1이므로. array_search를 사용한다.
                        $dExist = in_array( $dn, array_column($data, 'dataInfo') );
                        if($dExist) {
                            $tmp = array_search( $dn, array_column($data, 'dataInfo') );
                            
                            // TODO : 2022-12-02 ECMWF 예측기간(+150h)을 나머지 기간(+135h) 로 맞춰주는 작업.
                            $data_arr = array();
                            $fHeadUtc = array();
                            // if( $modl == "ECMW_NPPM" && $modl_size_num > 1 ) {
                            //     $data_arr = $this->suitForecastFormat($data[$tmp]['data'], "data");
                            //     $fHeadUtc = $this->suitForecastFormat($data[$tmp]['fHeadUtc'], "head");
                            // } else {
                                $data_arr = $data[$tmp]['data'];
                                $fHeadUtc = $data[$tmp]['fHeadUtc'];
                            // }

                            $dataArray = [
                                // 파일명 검사용.
                                'search' => $dExist . " || ",
                                'fileName' => $data[$tmp]['fileName'],
                                'fHeadUtc' => $fHeadUtc,
                                'fDataNum' => $data[$tmp]['fDataNum'],
                                'vrfy_loc' => $vf . "_mean",
                                'month' => substr($mon['data'], 0, 6),
                                //'ym_range' => $mon['ymRange'],
                                'utc' => $mon['utcInfo'],
                                'model' => $modl,
                                'num' => $modl_size_num,
                                'data' => $data_arr
                            ];
                            
                            array_push($mon_utc_modl, $dataArray);
                        } // End of "tmp(search결과값)" if문
                    } // End of "model_sel" foreach.
                } // End of "rangeMon" foreach.
                
                $vrfy_loc = [
                    'var_name' => $param['var_select'],
                    'vrfy_loc' => $vf . "_mean",
                    'data' => $mon_utc_modl
                ];
                
                array_push($resData, $vrfy_loc);
        }
        
        return $resData;
    }
























    
    
}
