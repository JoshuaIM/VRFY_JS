<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tstbcommon_func {
    
//////////////////////////////////////////////////////////////////////////////
/////   예측기간(월별) 단기*중기 모두 사용
//////////////////////////////////////////////////////////////////////////////
    
// View의 선택 값을 조합하여 파일명 생성 후
// 데이터 추출하는 메서드.
    public function getFcstData($fnParam) {

        // 예보활용도(utilize)에서 변수 선택이 복수가 되면서 기존 함수를 함께 사용할수 있도록 기존의 단일 요소 선택 값을 배열화 시킴. 2023-05-23
        $variable = $fnParam['var_select'];
        $var_arr = array();
        if( is_array($variable) == 1 ) {
            $var_arr = $variable;
        } else {
            array_push($var_arr, $variable);
        }

        $dataArr = array();
        
        // 순서: 검증지수 - 지점  - 월 - UTC - 모델
        foreach ($fnParam['vrfy_idx'] as $vrfy) {

            foreach ($fnParam['infoUTC'] as $utc) {

                foreach ($fnParam['rangeMon'] as $mon) {
                    
                    foreach ($fnParam['model_sel'] as $modl) {
                        
                        // 요소 배열 for문 추가. 2023-05-23
                        foreach ($var_arr as $var) {

                            // 정확도(GEMD)일 경우 예보편집 자료(디렉토리 경로가 다름)를 함께 표출하기 위해 사용.
                            $directory_head = $fnParam['dir_head'];

                            // 2023-05-25 추가문. 유사도에서는 "_VRFY_" 가 사용되지 않으므로 현재 함수를 공통으로 사용하기 위해 예외 처리 해줌.
                            $type_separator = "";
                            
                            if( $modl === "SSPS" ) {
                                $type_separator = "_VRFY_";
                            } else if( $modl === "GEMD" ) {
                                // 정확도(GEMD)일 경우
                                $type_separator = "_VRFY_";
                                $directory_head = $fnParam['gemd_dir_head'];
                            } else {
                                $exp_modl = explode("_", $modl);
                                // 유사도(GEMD)가 아니면
                                if(  $exp_modl[1] != "GEMD" ) {
                                    $type_separator = "_VRFY_";
                                // 유사도(GEMD)일 경우
                                } else {
                                    $type_separator = "_";
                                }                        
                            }

                            $ymDir = $mon['ymInfo'];
                            $modl_ym_dir = "/" . $ymDir . "/";
                            
                            // 파일명 조합.
                            // $tmpfn = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['data'];

                            // TODO: utc 분리 위해 사용 (잠시)
                            $explode_mon = explode("_", $mon['data']);
                            // 2023-05-23
                            // $tmpfn = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;
                            $tmpfn = $directory_head . $modl_ym_dir . $var . "/" . $fnParam['data_head'] . $modl . '_' . $var . $type_separator . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;
                            
                            // 2023-05-23
                            // $tmpfn_table = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/TBL/TBL_" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;
                            $tmpfn_table = $directory_head . $modl_ym_dir . $var . "/TBL/TBL_" . $fnParam['data_head'] . $modl . '_' . $var . $type_separator . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;

                            // 파일이 존재할 경우
                            if( file_exists($tmpfn) ) {
                                
                                // 줄 단위 파일 읽기.
                                $fileLine = explode("\n", file_get_contents($tmpfn));
                                
                                // 테이블 파일 읽어서 값 가져오기.
                                $tableLine = $this->getTableValue($tmpfn_table);

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
                                
                                // 권역평균 선택 시 사용.
                                if($fnParam['location'][0] == "mean") {
                                    $location_data_arr = array();
                                    $location_size = count($fnParam['location']);
                                }

                                // 파일별 지점 찾아 해당 데이터 얻기.
                                foreach ($fileLine as $line) {
                                    // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
                                    
                                    $loc = trim(substr($line, 0, 5));

                                    // 권역평균 선택 시.
                                    if($fnParam['location'][0] == "mean") {
                                        
                                        // $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                        // 2023-05-23
                                        // $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl;
                                        $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl . "_" . $var;
                                        $dArray = [
                                            // 파일명 검사용.
                                            'fileName' => $tmpfn,

                                            'tableFileName' => $tmpfn_table,
                                            'tableData' => $tableLine,

                                            //'dateInfo' => $fHeadDate,
                                            'fHeadUtc' => $fHeadUtc,
                                            'fDataNum' => $fDataNum,
                                            'dataInfo' => $dinfo,
                                        ];
                                        
                                        for( $lo=0; $lo<$location_size; $lo++ ) {
                                            if( $loc == $fnParam['location'][$lo] ) {
                                                // data의 정보.
                                                // $mkArr = $this->splitFcstData($line);
                                                $temporary_data = $this->splitFcstData($line);
                                                // 2023-05-23
                                                // $var = $fnParam['var_select'];
                                                $modl_name = $modl;
                                                $modl_arr_size = sizeof($fnParam['model_sel']);
                                                $mkArr = $this->extractModlData($temporary_data, $var, $modl_name, $modl_arr_size);

                                                array_push($location_data_arr, $mkArr);
                                                // array_push($dataArr, $mkArr);

                                                if( $lo == $location_size-1 ) {
                                                    $dArray['data'] = $this->getMeanData($location_data_arr);
                                                    $dArray['all_location_data'] = $location_data_arr;
                                                    array_push($dataArr, $dArray);
                                                }
                                            }
                                        }

                                    // 권역평균 외 모든 영역 선택 시.
                                    } else {

                                        foreach ($fnParam['location'] as $mat) {
                                            if( $loc == $mat ) {
                                                // data의 정보.
                                                // $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                                // 2023-05-23
                                                // $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl;
                                                $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl . "_" . $var;
                                                // $mkArr = $this->splitFcstData($line);
                                                $temporary_data = $this->splitFcstData($line);
                                                // 2023-05-23
                                                // $var = $fnParam['var_select'];
                                                $modl_name = $modl;
                                                $modl_arr_size = sizeof($fnParam['model_sel']);
                                                $mkArr = $this->extractModlData($temporary_data, $var, $modl_name, $modl_arr_size);

                                                $dArray = [
                                                    // 파일명 검사용.
                                                    'fileName' => $tmpfn,

                                                    'tableFileName' => $tmpfn_table,
                                                    'tableData' => $tableLine,

                                                    //'dateInfo' => $fHeadDate,
                                                    'fHeadUtc' => $fHeadUtc,
                                                    'fDataNum' => $fDataNum,
                                                    'dataInfo' => $dinfo,
                                                    'data' => $mkArr
                                                ];
                                                array_push($dataArr, $dArray);
                                            }
                                        } // End of "Location" foreach.
                                        
                                    } // End of $fnParam['location'][0] == "mean"인지 확인하는 if 문.

                                } // End of "File Line" foreach.
                            }
                            // "File exist" false 의 경우
                            else
                            {
                                $dArray = [
                                    'fileName' => $tmpfn,
                                    'tableFileName' => null,
                                    'tableData' => null,
                                    'fHeadUtc' => null,
                                    'fDataNum' => null,
                                    'dataInfo' => null,
                                    'data' => null
                                ];
                                array_push($dataArr, $dArray);
                            } // End of "File exist" if 문.
                        } // End of "VAR" foreach.
                    } // End of "MODEL" foreach.
                } // End of "RANGEMON" foreach.
            } // End of "UTC" foreach.
        } // End of "VRFY IDX" foreach.
        
        return $dataArr;
    }



    // View의 선택 값을 조합하여 파일명 생성 후
    // 데이터 추출하는 메서드.
    public function getGemdFcstData($fnParam) {

        // 예보활용도(utilize)에서 변수 선택이 복수가 되면서 기존 함수를 함께 사용할수 있도록 기존의 단일 요소 선택 값을 배열화 시킴. 2023-05-23
        $variable = $fnParam['var_select'];
        $var_arr = array();
        if( is_array($variable) == 1 ) {
            $var_arr = $variable;
        } else {
            array_push($var_arr, $variable);
        }

        $dataArr = array();
        
        // 순서: 검증지수 - 지점  - 월 - UTC - 모델
        foreach ($fnParam['vrfy_idx'] as $vrfy) {

            foreach ($fnParam['infoUTC'] as $utc) {

                foreach ($fnParam['rangeMon'] as $mon) {
                    
                    // 정확도의 모델은 단일 선택으로 운영.
                    // - 단 선택 된 모델 검증자료와 예보편집 자료가 무조건 함께 표출.
                    foreach ($fnParam['model_sel'] as $modl) {
                        
                        // 요소 배열 for문 추가. 2023-05-23
                        foreach ($var_arr as $var) {
                        
                            // 2023-05-25 추가문. 유사도에서는 "_VRFY_" 가 사용되지 않으므로 현재 함수를 공통으로 사용하기 위해 예외 처리 해줌.
                            $type_separator = "";

                            $type_separator = "_VRFY_";

                            $ymDir = $mon['ymInfo'];
                            $modl_ym_dir = "/" . $ymDir . "/";

                            // TODO: utc 분리 위해 사용 (잠시)
                            $explode_mon = explode("_", $mon['data']);
                            // 2023-05-23
                            // $tmpfn = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;
                            $tmpfn = $fnParam['dir_head'] . $modl_ym_dir . $var . "/" . $fnParam['data_head'] . $modl . '_' . $var . $type_separator . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;
                            
                            // 2023-05-23
                            // $tmpfn_table = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/TBL/TBL_" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;
                            $tmpfn_table = $fnParam['dir_head'] . $modl_ym_dir . $var . "/TBL/TBL_" . $fnParam['data_head'] . $modl . '_' . $var . $type_separator . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;

                            // 파일이 존재할 경우
                            if( file_exists($tmpfn) ) {
                                
                                // 줄 단위 파일 읽기.
                                $fileLine = explode("\n", file_get_contents($tmpfn));
                                
                                // 테이블 파일 읽어서 값 가져오기.
                                $tableLine = $this->getTableValue($tmpfn_table);

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
                                
                                // 권역평균 선택 시 사용.
                                if($fnParam['location'][0] == "mean") {
                                    $location_data_arr = array();
                                    $location_size = count($fnParam['location']);
                                }

                                // 파일별 지점 찾아 해당 데이터 얻기.
                                foreach ($fileLine as $line) {
                                    // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
                                    
                                    $loc = trim(substr($line, 0, 5));

                                    // 권역평균 선택 시.
                                    if($fnParam['location'][0] == "mean") {
                                        
                                        // $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                        // 2023-05-23
                                        // $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl;
                                        $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl . "_" . $var;
                                        $dArray = [
                                            // 파일명 검사용.
                                            'fileName' => $tmpfn,

                                            'tableFileName' => $tmpfn_table,
                                            'tableData' => $tableLine,

                                            //'dateInfo' => $fHeadDate,
                                            'fHeadUtc' => $fHeadUtc,
                                            'fDataNum' => $fDataNum,
                                            'dataInfo' => $dinfo,
                                        ];
                                        
                                        for( $lo=0; $lo<$location_size; $lo++ ) {
                                            if( $loc == $fnParam['location'][$lo] ) {
                                                // data의 정보.
                                                // $mkArr = $this->splitFcstData($line);
                                                $temporary_data = $this->splitFcstData($line);
                                                // 2023-05-23
                                                // $var = $fnParam['var_select'];
                                                $modl_name = $modl;
                                                $modl_arr_size = sizeof($fnParam['model_sel']);
                                                $mkArr = $this->extractModlData($temporary_data, $var, $modl_name, $modl_arr_size);

                                                array_push($location_data_arr, $mkArr);
                                                // array_push($dataArr, $mkArr);

                                                if( $lo == $location_size-1 ) {
                                                    $dArray['data'] = $this->getMeanData($location_data_arr);
                                                    $dArray['all_location_data'] = $location_data_arr;
                                                    array_push($dataArr, $dArray);
                                                }
                                            }
                                        }

                                    // 권역평균 외 모든 영역 선택 시.
                                    } else {

                                        foreach ($fnParam['location'] as $mat) {
                                            if( $loc == $mat ) {
                                                // data의 정보.
                                                // $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                                // 2023-05-23
                                                // $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl;
                                                $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl . "_" . $var;
                                                // $mkArr = $this->splitFcstData($line);
                                                $temporary_data = $this->splitFcstData($line);
                                                // 2023-05-23
                                                // $var = $fnParam['var_select'];
                                                $modl_name = $modl;
                                                $modl_arr_size = sizeof($fnParam['model_sel']);
                                                $mkArr = $this->extractModlData($temporary_data, $var, $modl_name, $modl_arr_size);

                                                $dArray = [
                                                    // 파일명 검사용.
                                                    'fileName' => $tmpfn,

                                                    'tableFileName' => $tmpfn_table,
                                                    'tableData' => $tableLine,

                                                    //'dateInfo' => $fHeadDate,
                                                    'fHeadUtc' => $fHeadUtc,
                                                    'fDataNum' => $fDataNum,
                                                    'dataInfo' => $dinfo,
                                                    'data' => $mkArr
                                                ];
                                                array_push($dataArr, $dArray);
                                            }
                                        } // End of "Location" foreach.
                                        
                                    } // End of $fnParam['location'][0] == "mean"인지 확인하는 if 문.

                                } // End of "File Line" foreach.
                            } // End of "File exist" if 문.
                            else {
                                array_push($dataArr, $tmpfn);
                            }

                        } // End of "VAR" foreach.

                    } // End of "MODEL" foreach.
                } // End of "RANGEMON" foreach.

            } // End of "UTC" foreach.

        } // End of "VRFY IDX" foreach.
        
        return $dataArr;
    }






// 데이터 파일 헤더의 UTC 정보 제공.
    public function getFileHeadUtc($hourLine) {
        $utcInfo = array();

        $rep_whitesp = trim( preg_replace('/\s+/', '#', $hourLine) );
        
        $hourStr = explode("#", $rep_whitesp);
        
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
                
                foreach ($param['infoUTC'] as $utc) {

                    $mon_utc_modl = array();
                    foreach ($param['rangeMon'] as $mon) {

                        $modl_size_num = sizeof($param['model_sel']);
                        foreach ($param['model_sel'] as $modl) {
                            
                            // $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                            // 2023-05-23
                            // $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl;
                            $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl . "_" . $param['var_select'];
                            
                            // TODO: $tmp(search결과 array key값)이 없을 경우와 1번째 배열의 결과값 0 둘다 if문에서는 false 이다... PHP잘못된 디자인의 프랙탈.
                            // 그러므로 in_array를 사용하여 배열값을 확인하고 -> 있으면 결과값이 1이므로. array_search를 사용한다.
                            $dExist = in_array( $dn, array_column($data, 'dataInfo') );
                            if($dExist) {
                                $tmp = array_search( $dn, array_column($data, 'dataInfo') );
                                
                                // TODO : 2022-12-02 ECMWF 예측기간(+150h)을 나머지 기간(+135h) 로 맞춰주는 작업.
                                $data_arr = array();
                                $fHeadUtc = array();
                                $data_arr = $data[$tmp]['data'];
                                $fHeadUtc = $data[$tmp]['fHeadUtc'];

                                $dataArray = [
                                    // 파일명 검사용.
                                    'search' => $dExist . " || ",
                                    'fileName' => $data[$tmp]['fileName'],

                                    'tableFileName' => $data[$tmp]['tableFileName'],
                                    'tableData' => $data[$tmp]['tableData'],

                                    'fHeadUtc' => $fHeadUtc,
                                    'fDataNum' => $data[$tmp]['fDataNum'],
                                    'vrfy_loc' => $vf . "_" . $lc,
                                    'month' => substr($mon['data'], 0, 6),
                                    //'ym_range' => $mon['ymRange'],
                                    // 'utc' => $mon['utcInfo'],
                                    'utc' => $utc . "UTC",
                                    'model' => $modl,
                                    'modl_color' => $this->getModelColor($modl),
                                    'num' => $modl_size_num,
                                    'data' => $data_arr,
                                    'dn' => $dn
                                ];
                                
                                array_push($mon_utc_modl, $dataArray);
                            } // End of "tmp(search결과값)" if문
                        } // End of "model_sel" foreach.
                    } // End of "rangeMon" foreach.
                    
                    $vrfy_loc = [
                        'var_name' => $param['var_select'],
                        'vrfy_loc' => $vf . "_" . $lc,
                        // 'utc' => $mon['utcInfo'],
                        'utc' => $utc,
                        'data' => $mon_utc_modl
                    ];
                    
                    array_push($resData, $vrfy_loc);
                }

            }

        }
        
        return $resData;
    }



    public function arrangeGemdUtilizeFcstData($data, $param) {
        
        $resData = array();
        foreach ($param['vrfy_idx'] as $vf) {

            foreach ($param['location'] as $lc) {
                
                foreach ($param['infoUTC'] as $utc) {

                    foreach ($param['rangeMon'] as $mon) {
                        
                        foreach ($param['model_sel'] as $modl) {
                            
                            $mon_utc_modl = array();
                            $modl_size_num = sizeof($param['model_sel']);
                            foreach ($param['var_select'] as $var) {

                                $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl . "_" . $var;
                                
                                // TODO: $tmp(search결과 array key값)이 없을 경우와 1번째 배열의 결과값 0 둘다 if문에서는 false 이다... PHP잘못된 디자인의 프랙탈.
                                // 그러므로 in_array를 사용하여 배열값을 확인하고 -> 있으면 결과값이 1이므로. array_search를 사용한다.
                                $dExist = in_array( $dn, array_column($data, 'dataInfo') );
                                if($dExist) {
                                    $tmp = array_search( $dn, array_column($data, 'dataInfo') );
                                    
                                    // TODO : 2022-12-02 ECMWF 예측기간(+150h)을 나머지 기간(+135h) 로 맞춰주는 작업.
                                    $data_arr = array();
                                    $fHeadUtc = array();
                                    $data_arr = $data[$tmp]['data'];
                                    $fHeadUtc = $data[$tmp]['fHeadUtc'];

                                    $dataArray = [
                                        // 파일명 검사용.
                                        'search' => $dExist . " || ",
                                        'fileName' => $data[$tmp]['fileName'],

                                        'tableFileName' => $data[$tmp]['tableFileName'],
                                        'tableData' => $data[$tmp]['tableData'],

                                        'fHeadUtc' => $fHeadUtc,
                                        'fDataNum' => $data[$tmp]['fDataNum'],
                                        'var' => $var,
                                        'vrfy_loc' => $vf . "_" . $lc,
                                        'month' => substr($mon['data'], 0, 6),
                                        //'ym_range' => $mon['ymRange'],
                                        // 'utc' => $mon['utcInfo'],
                                        'utc' => $utc . "UTC",
                                        'model' => $modl,
                                        'modl_color' => $this->getModelColor($modl),
                                        'variable_color' => $this->getVariableColor($var),
                                        'num' => $modl_size_num,
                                        'data' => $data_arr,
                                        'dn' => $dn
                                    ];
                                    
                                    array_push($mon_utc_modl, $dataArray);
                                } // End of "tmp(search결과값)" if문


                            } // End of "var_sel" foreach.

                            $vrfy_loc = [
                                // 'var_name' => $var,
                                'vrfy_loc' => $vf . "_" . $lc,
                                'month' => $mon,
                                'model' => $modl,
                                'utc' => $utc,
                                'data' => $mon_utc_modl
                            ];
                            array_push($resData, $vrfy_loc);

                        } // End of "model_sel" foreach.
                    } // End of "rangeMon" foreach.

                }

            }
        }
        
        return $resData;
    }
    // public function arrangeFcstData($data, $param) {
        
    //     $resData = array();
    //     foreach ($param['vrfy_idx'] as $vf) {

    //         foreach ($param['location'] as $lc) {
                
    //             $mon_utc_modl = array();
    //             foreach ($param['rangeMon'] as $mon) {

    //                 $modl_size_num = sizeof($param['model_sel']);
    //                 foreach ($param['model_sel'] as $modl) {
                        
    //                     $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                        
    //                     // TODO: $tmp(search결과 array key값)이 없을 경우와 1번째 배열의 결과값 0 둘다 if문에서는 false 이다... PHP잘못된 디자인의 프랙탈.
    //                     // 그러므로 in_array를 사용하여 배열값을 확인하고 -> 있으면 결과값이 1이므로. array_search를 사용한다.
    //                     $dExist = in_array( $dn, array_column($data, 'dataInfo') );
    //                     if($dExist) {
    //                         $tmp = array_search( $dn, array_column($data, 'dataInfo') );
                            
    //                         // TODO : 2022-12-02 ECMWF 예측기간(+150h)을 나머지 기간(+135h) 로 맞춰주는 작업.
    //                         $data_arr = array();
    //                         $fHeadUtc = array();
    //                         $data_arr = $data[$tmp]['data'];
    //                         $fHeadUtc = $data[$tmp]['fHeadUtc'];

    //                         $dataArray = [
    //                             // 파일명 검사용.
    //                             'search' => $dExist . " || ",
    //                             'fileName' => $data[$tmp]['fileName'],
    //                             'fHeadUtc' => $fHeadUtc,
    //                             'fDataNum' => $data[$tmp]['fDataNum'],
    //                             'vrfy_loc' => $vf . "_" . $lc,
    //                             'month' => substr($mon['data'], 0, 6),
    //                             //'ym_range' => $mon['ymRange'],
    //                             'utc' => $mon['utcInfo'],
    //                             'model' => $modl,
    //                             'modl_color' => $this->getModelColor($modl),
    //                             'num' => $modl_size_num,
    //                             'data' => $data_arr
    //                         ];
                            
    //                         array_push($mon_utc_modl, $dataArray);
    //                     } // End of "tmp(search결과값)" if문
    //                 } // End of "model_sel" foreach.
    //             } // End of "rangeMon" foreach.
                
    //             $vrfy_loc = [
    //                 'var_name' => $param['var_select'],
    //                 'vrfy_loc' => $vf . "_" . $lc,
    //                 'data' => $mon_utc_modl
    //             ];
                
    //             array_push($resData, $vrfy_loc);
    //         }
    //     }
        
    //     return $resData;
    // }

    

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

                foreach ($fnParam['infoUTC'] as $utc) {
                
                    foreach ($fnParam['model_sel'] as $modl) {
                        $ymDir = $mon['ymInfo'];
                        
                        $modl_ym_dir = "/" . $ymDir . "/";
                        
                        // 파일명 조합.
                        // $tmpfn = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['data'];

                        // TODO: utc 분리 위해 사용 (잠시)
                        $explode_mon = explode("_", $mon['data']);
                        $tmpfn = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;
                        
                        // 파일이 존재할 경우
                        if( file_exists($tmpfn) ) {
                            
                            // 줄 단위 파일 읽기.
                            $fileLine = explode("\n", file_get_contents($tmpfn));
                            
                            // 권역평균 선택 시 사용.
                            if($fnParam['location'][0] == "mean") {
                                $location_data_arr = array();
                                $location_size = count($fnParam['location']);
                            }
                            
                            // 파일별 지점 찾아 해당 데이터 얻기.
                            foreach ($fileLine as $line) {
                                // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
                                $loc = trim(substr($line, 0, 5));
                                
                                // 권역평균 선택 시.
                                if($fnParam['location'][0] == "mean") {
                                    
                                    $dinfo = $vrfy . "_mean_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                    $dArray = [
                                        // 파일명 검사용.
                                        'monthInfo' => $mon['ymInfo'],
                                        'utcInfo' => $mon['utcInfo'],
                                        'fileName' => $tmpfn,
                                        'dataInfo' => $dinfo,
                                    ];
                                    
                                    for( $lo=0; $lo<$location_size; $lo++ ) {
                                        if( $loc == $fnParam['location'][$lo] ) {
                                            // data의 정보.
                                            $mkArr = $this->splitMonthData($line);
                                            array_push($location_data_arr, $mkArr);
                                            // array_push($dataArr, $mkArr);

                                            if( $lo == $location_size-1 ) {
                                                $dArray['data'] = [$this->getMeanRoundsData($location_data_arr)];
                                                // $dArray['data'] = $location_data_arr;
                                                $dArray['all_location_data'] = $location_data_arr;
                                                array_push($dataArr, $dArray);
                                            }
                                        }
                                    }

                                // 권역평균 외 모든 영역 선택 시.
                                } else {
                                
                                    foreach ($fnParam['location'] as $mat) {
                                        if( $loc == $mat ) {
                                            // data의 정보.
                                            // $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                            $dinfo = $vrfy . "_" . $loc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl;

                                            $mkArr = $this->splitMonthData($line);
                                            
                                            $dArray = [
                                                // 파일명 검사용.
                                                'monthInfo' => $mon['ymInfo'],
                                                // 'utcInfo' => $mon['utcInfo'],
                                                'utcInfo' => $utc,
                                                'fileName' => $tmpfn,
                                                'dataInfo' => $dinfo,
                                                'data' => $mkArr
                                            ];
                                            array_push($dataArr, $dArray);
                                        }
                                    } // End of "Location" foreach.

                                } // End of $fnParam['location'][0] == "mean"인지 확인하는 if 문.

                            } // End of "File Line" foreach.

                        } // End of "MONTH" foreach.

                    } // End of "File exist" if 문.

                }

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
                
                foreach ($param['infoUTC'] as $utc) {                

                    $mon_utc_modl = array();
                    foreach ($param['model_sel'] as $modl) {

                        // TODO: 2019-09-23 초기시각 멀티셀렉션으로 바뀌면서 소스 수정함. ( 초기시각 값이 단일 값으로 오던게 -> array 로 넘어옴. )
                        // for($i=0; $i<sizeof($param['init_hour']); $i++) {
        
                        //     $utc_val = $param['init_hour'][$i];
                        //         $targUTC = explode("#" , $utc_val);
                        //         $strtUTC = $targUTC[0];
                        //         $endUTC = $targUTC[1];
                        //     $utc_comp = $strtUTC . "UTC";
                        //         if( $strtUTC != $endUTC ) {
                        //             $utc_comp = $strtUTC . "-" . $endUTC . "UTC";
                        //         }
                            
                            // 모아둔 month 데이터의 . (UTC는 별도)
                            $mon_range= array();
                            // month 데이터를 합친 배열. (UTC는 별도)
                            $mon_integ = array();
                            // $utc_info = "";
                            foreach ($param['rangeMon'] as $mon) {
                                
                                // if ( $utc_comp == $mon['utcInfo'] ) {
                                    
                                    // $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $mon['utcInfo'] . "_" . $modl;
                                    $dn = $vf . "_" . $lc . "_" . substr($mon['data'], 0, 6) . "_" . $utc . "_" . $modl;
                                    
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
                                    
                                    // $utc_info = $mon['utcInfo'];
                                    $utc_info = $utc . "UTC";
                                    array_push( $mon_range, substr($mon['data'], 0, 6) );
                                // }
                            }
                            // End of foreach 'rangeMon'.
                            
                            $dataArr = [
                                'model' => $modl,
                                'utcInfo' => $utc_info,
                                'mon_range' => $mon_range,
                                'modl_color' => $this->getModelColor($modl),
                                'data' => $mon_integ
                            ];
                            
                            array_push( $mon_utc_modl, $dataArr);                                
                        // }
                                
                    } // End of "model_sel" foreach.
                    
                    $vrfy_loc = [
                        'var_name' => $param['var_select'],
                        'vrfy_loc' => $vf . "_" . $lc,
                        'utc' => $utc,
                        'data' => $mon_utc_modl
                    ];

                    array_push($resData, $vrfy_loc);
                    
                }

                // $vrfy_loc = [
                //     'var_name' => $param['var_select'],
                //     'vrfy_loc' => $vf . "_" . $lc,
                //     'data' => $mon_utc_modl
                //     'utc' => $utc
                // ];
                
                // array_push($resData, $vrfy_loc);
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
        
        $rep_whitesp = trim( preg_replace('/\s+/', '#', $hourLine) );
        
        $hourStr = explode("#", $rep_whitesp);
        
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
    


    // 2023-01-17 복수 모델 선택 중 ECMWF가 포함되었을 시 135H 으로 맞추기 위해 선별하는 작업.
    // 2023-03-20 "sizeof($data) > 100" 추가 -> 중기자료는 ECMWF도 자료수가 같다. 그러므로 중기자료는 제외시키기 위함. (중기자료수 = 96개)
    public function extractModlData($data, $var, $modl_name, $modl_arr_size) {
        if( ($modl_name == "ECMW_NPPM" && $modl_arr_size > 1 && sizeof($data) > 100 ) ) {
            if( $var != "TMN" && $var != "TMX" ) {
                $mkArr = $this->suitForecastFormat($data, "data");
            } else {
                $mkArr = $data;
            }
        // data size 가 달라서 위의 IF 문에서 SN3 만 골라내지를 못함.
        } else if( ($modl_name == "ECMW_NPPM" && $modl_arr_size > 1 && sizeof($data) > 45 && sizeof($data) < 50 && $var === "SN3" ) ) {
            $mkArr = $this->suitForecastFormatSN3($data, "data");
        } else {
            $mkArr = $data;
        }

        return $mkArr;
    }



    // 2022-12-02 ECMWF 예측기간(+150h)을 나머지 기간(+135h) 로 맞춰주는 작업.
    public function suitForecastFormat($data, $type) {
        $sp_data = $data;
        $size = sizeof($sp_data);
        $res = array_splice($sp_data, 0, 130);
        if( $type == "data"){
            array_push($res, $data[$size-1]);
        }

        return $res;
    }
    // 단기 SN1이 SN3로 변경되며 ECMWF 예측기간(+47h)을 나머지 기간(+44h) 로 맞춰주는 작업.
    public function suitForecastFormatSN3($data, $type) {
        $sp_data = $data;
        $size = sizeof($sp_data);
        $res = array_splice($sp_data, 0, 44);
        if( $type == "data"){
            array_push($res, $data[$size-1]);
        }

        return $res;
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



    public function getModelColor($modl) {

        $file_path = "./assets/modl_tech_conf/modl_tech_color.conf";

        if( file_exists($file_path) ) {

            $confLine = explode("\n", file_get_contents($file_path));
            $modl_match = preg_grep("/".$modl."/", $confLine);

            $color = "";
            foreach( $modl_match as $val ) {
                $explode_match_modl = explode(":", $val);
                $color = trim($explode_match_modl[1]);
            }
            return $color;
            // return $modl_match;

        } else {
            return "No have model config file : " . $file_path;
        }

    }



    public function getVariableColor($var) {

        $file_path = "./assets/modl_tech_conf/var_tech_color.conf";

        if( file_exists($file_path) ) {

            $confLine = explode("\n", file_get_contents($file_path));
            $var_match = preg_grep("/".$var."/", $confLine);

            $color = "";
            foreach( $var_match as $val ) {
                $explode_match_var = explode(":", $val);
                $color = trim($explode_match_var[1]);
            }
            return $color;
            // return $modl_match;

        } else {
            return "No have model config file : " . $file_path;
        }

    }



    public function getTableValue($file_name) {

        $data_arr = array();
        if( file_exists($file_name) ) {
                            
            // 줄 단위 파일 읽기.
            $fileLine = explode("\n", file_get_contents($file_name));

            $ave_match = preg_grep("/AVE,/", $fileLine);
            foreach( $ave_match as $match ) {
                $data_arr = explode(",",$match);
            }
        } else {

        }

        return $data_arr;
    }













    
    
}
