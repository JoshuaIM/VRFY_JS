<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mapcommon_func {
    
//////////////////////////////////////////////////////////////////////////////
/////   예측기간(월별) 단기*중기 모두 사용
//////////////////////////////////////////////////////////////////////////////
    
// 공간분포 지점에서 데이터의 헤더정보 중 예보시간을 읽어서 리턴하는 메서드.
    public function getFcstInfo($fnParam) {
        
        $test = array();       
        
        // 순서: 검증지수 - 지점  - 월 - UTC - 모델
        foreach ($fnParam['vrfy_idx'] as $vrfy) {

            foreach ($fnParam['rangeMon'] as $mon) {
                
                foreach ($fnParam['model_sel'] as $modl) {
                    
                    $ymDir = $mon['ymInfo'];
                    
                    $modl_ym_dir = "/" . $ymDir . "/";
                    
                    // 파일명 조합.
                    $tmpfn = $fnParam['dir_head'] . $modl_ym_dir . $fnParam['var_select'] . "/" . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['data'];
		    //$tmpfn = $fnParam['dir_name'] . $fnParam['data_head'] . $modl . '_' . $fnParam['var_select'] . '_VRFY_' . $vrfy . '.' . $mon['range_mon'];
                    
                    
//                     array_push($test, $tmpfn);
                    // 파일이 존재할 경우
                    if( file_exists($tmpfn) ) {
//                     array_push($test, $tmpfn);
                        
                        // 줄 단위 파일 읽기.
                        $fileLine = explode("\n", file_get_contents($tmpfn));
                        
                        // 데이터 파일 헤더의 UTC 정보 제공. ( 그래프 X축의 forecast 시간 표출용 )
                        $fHeadUtc = $this->getMapFileHeadUtc($fileLine[1], $fnParam['peri_type']);
                        
                        if( sizeof($fHeadUtc) > 1 ) {
                            return $fHeadUtc;    
                        }
                        
                    } // End of "File exist" if 문.
                } // End of "MODEL" foreach.
            } // End of "RANGEMON" foreach.
        } // End of "VRFY IDX" foreach.
        
//         return $test;
    }
    
    
    
//////////////////////////////////////////////////////////////////////////////
/////   임의기간 단기*중기 모두 사용
//////////////////////////////////////////////////////////////////////////////
    
// 공간분포 지점에서 데이터의 헤더정보 중 예보시간을 읽어서 리턴하는 메서드.
    public function getArbiInfo($fnParam) {
        
        // 순서: 검증지수 - 지점  - 월 - UTC - 모델
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
                        $fHeadUtc = $this->getMapFileHeadUtc($fileLine[1], $fnParam['peri_type']);
                        
                        // 하나의 데이터 파일 헤더만 확인 후 종료.
                        if( sizeof($fHeadUtc) > 1 ) {
                            return $fHeadUtc;
                        }
                        
                    } // End of "File exist" if 문.
                } // End of "MODEL" foreach.
            } // End of "RANGEMON" foreach.
        } // End of "VRFY IDX" foreach.
    }
    
    
    
    
    
    
// 임의기간의 경우 시작시간 또는 목표시간 데이터 파일 헤더의 UTC 정보 제공.
    public function getMapFileHeadUtc($hourLine, $peri_type) {
        $utcIdx = array();
        $utcTxt = array();
        
        $rep_whitesp = preg_replace('/[H]/', ' ', $hourLine);
        
        if($peri_type == "TARGET") {
            $hourStr = explode("-", $rep_whitesp);
        } else {
            $hourStr = explode("+", $rep_whitesp);
        }
        
        // 첫번째에 AVE 표출하도록 추가.
        array_push($utcIdx, "AVE");
        array_push($utcTxt, "AVE");
         
        // 첫번째 배열은 'STNID  '이므로 필요 없음.
        // +뒤에 바로 숫자로 나오게 하기 위해 int로 바꿨다가 다시 string으로 변환하면서 필요한 문자 삽입.
        for( $h=1; $h<sizeof($hourStr); $h++ ) {
            $idxH = trim($hourStr[$h]);
            $tmpH = (int)$hourStr[$h];
                if($peri_type == "TARG") {
                    $resH = "-" . strval($tmpH) . "H";
                } else {
                    $resH = "+" . strval($tmpH) . "H";
                }
            
            array_push($utcIdx, $idxH);
            array_push($utcTxt, $resH);
        }
        
        $utcInfo = [
            'utc_idx' => $utcIdx,
            'utc_txt' => $utcTxt
        ];
        
        return $utcInfo;
    }
    
    
    
    
    
    
}
