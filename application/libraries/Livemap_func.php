<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Livemap_func {

//////////////////////////////////////////////////////////////////////////////
/////   공간분포 실시간 자료 추출
//////////////////////////////////////////////////////////////////////////////
    
// View의 선택 값을 조합하여 파일명 생성 후
// 데이터 추출하는 메서드.
    public function get_map_live_data($f_param)
    {
        $var = $f_param['var_select'];
        // 공간분포는 UTC 하나만 넘어옴
        $utc = $f_param["infoUTC"][0];

        $result = [
            "value" => array()
        ];
        
        // 순서: 검증지수 - 월 - UTC - 모델
        foreach ($f_param['vrfy_idx'] as $vrfy)
        {
            foreach ($f_param['rangeMon'] as $mon)
            {
                foreach ($f_param['model_sel'] as $modl)
                {
                    // 정확도(GEMD)일 경우 예보편집 자료(디렉토리 경로가 다름)를 함께 표출하기 위해 사용.
                    $directory_head = $f_param['dir_head'];

                    // 2023-05-25 추가문. 유사도에서는 "_VRFY_" 가 사용되지 않으므로 현재 함수를 공통으로 사용하기 위해 예외 처리 해줌.
                    $type_separator = "";
                    $exp_modl = explode("_", $modl);
                    $type_separator = "_VRFY_";

                    $ym_dir = $mon['ymInfo'];
                    $modl_ym_dir = "/" . $ym_dir . "/";
                    
                    $explode_mon = explode("_", $mon['data']);
                    $tmp_fn = $directory_head . $modl_ym_dir . $var . "/" . $f_param['data_head'] . $modl . '_' . $var . $type_separator . $vrfy . '.' . $explode_mon[0] . $utc . "_" . $explode_mon[1] . $utc;

                    // 파일이 존재할 경우
                    if (file_exists($tmp_fn))
                    {
                        // 줄 단위 파일 읽기.
                        $file_line = explode("\n", file_get_contents($tmp_fn));
                        
                        // 데이터 파일 헤더의 UTC 정보 제공. ( 그래프 X축의 forecast 시간 표출용 )
                        $fHeadUtc = $this->getFileHeadUtc($file_line[1]);

                        $file_name = $tmp_fn;
                        $fc = $fHeadUtc;
                        $file_key = $vrfy . "_" . $ym_dir . "_" . $modl; 

                        $data_arr = [
                            "file_name" => $file_name,
                            "forecast_range" => $fc,
                            "f_key" => $file_key,
                        ];

                        $line_data_arr = array();

                        $line_count = 0;
                        foreach ($file_line as $line)
                        {
                            if ($line_count < 3)
                            {
                                $line_count++;
                                continue;
                            }
                            // 매 줄 앞 5칸만 잘라내어 트림. (지점 번호 또는 AVE, NUM 정보 등등)
                            $loc = trim(substr($line, 0, 5));

                            // STNID는 5칸이므로 데이터에서 0-5칸 제외시킨다.
                            $stn_id_num = 5;
                            $split_stn_id = substr($line, $stn_id_num, strlen($line));

                            // whitespace(연속)를 '#'으로 변경한다.
                            $rep_whitespace = trim( preg_replace('/\s+/', '#', $split_stn_id) );
                            // '#'로 잘라서 배열에 넣는다.
                            $data = explode("#", $rep_whitespace);

                            if ($loc AND $loc != "NUM" AND $loc != "AVE")
                            {
                                $data[0] = $loc;
                                array_push($line_data_arr, $data);
                            }
                        } // End of "File Line" foreach.

                        if (!array_key_exists("station", $result))
                        {
                            $station_arr = array_column($line_data_arr, 0);
                            $result["station"] = $station_arr;

                            $lat_lon_info_txt = "./assets/station_info/dfs_vrfy_station_directory.dat";
                            $lat_lon = $this->get_lat_lon_info($lat_lon_info_txt, $station_arr);
                            $result["lat"] = $lat_lon["lat"];
                            $result["lon"] = $lat_lon["lon"];
                            $result["forecast_range"] = $fc;
                        }

                        for ($d=0; $d<sizeof($fc); $d++)
                        {
                            $data_arr[$fc[$d]] = array_column($line_data_arr, $d+1);
                        }

                        array_push($result["value"], $data_arr);
                    }
                    // "File exist" false 의 경우
                    else
                    {
                        $result = [
                            'fileName' => $tmp_fn,
                            'fHeadUtc' => null,
                            // 'fDataNum' => null,
                            // 'dataInfo' => null,
                            'data' => null
                        ];
                        // array_push($data_arr, $dArray);
                    } // End of "File exist" if 문.
                } // End of "MODEL" foreach.
            } // End of "RANGEMON" foreach.
        } // End of "VRFY IDX" foreach.
        
        return $result;
    }



// 데이터 파일 헤더의 UTC 정보 제공.
    public function getFileHeadUtc($hour_line)
    {
        $utc_info = array();

        $rep_whitesp = trim( preg_replace('/\s+/', '#', $hour_line) );
        $hour_str = explode("#", $rep_whitesp);
        
        // 첫번째 배열은 'STNID  '이므로 필요 없음.
        for ($h=1; $h<sizeof($hour_str); $h++)
        {
            // 앞의 기호 값 저장.
            $comp = substr( $hour_str[$h], 0, 1 );
            // 앞의 '+' 또는 '-' 문자와 맨뒤의 'H' 제외.
            $n = substr( $hour_str[$h], 1, 3 );
            // 예측시간 헤더가 숫자이면 int로 변화하여 표출하고 아니면 그대로 표출.
            if (is_numeric($n))
            {
                $tmp_h = (int)$n;
                $res_h = $comp . strval($tmp_h) . "H";
            }
            else
            {
                $res_h = $comp . $n . "H";
            }
            array_push($utc_info, $res_h);
        }
        
        array_push($utc_info, "AVE");
        return $utc_info;
    }
    
    
    
    public function get_lat_lon_info($lat_lon_info_txt, $station_arr)
    {
        $lat_lon_arr = [
            "lat" => array(),
            "lon" => array()
        ];

        if (file_exists($lat_lon_info_txt))
        {
            // 줄 단위 파일 읽기.
            $file = explode("\n", file_get_contents($lat_lon_info_txt));

            foreach ($station_arr as $stn)
            {
                $match_m = preg_grep("/".$stn."/", $file);
                $lat_lon = $this->get_lat_lon_value($match_m[key($match_m)]);

                array_push($lat_lon_arr["lat"], $lat_lon["lat"]);
                array_push($lat_lon_arr["lon"], $lat_lon["lon"]);
            }
        }
        
        return $lat_lon_arr;
    }
    public function get_lat_lon_value($line)
    {
        $line_whitesp = trim( preg_replace('/\s+/', '#', $line) );
        $val_arr = explode("#", $line_whitesp);

        $lat_lon = [
            "lat" => $val_arr[4],
            "lon" => $val_arr[5]
        ];
        return $lat_lon;
    }






    
    
}
