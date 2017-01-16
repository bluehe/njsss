<?php

//生成广告代码
function advcode($style, $paraments) {
    global $INI;
    if ($style == 'code') {
        if ($paraments['html'] == "") {
            return false;
        }
        $code = $paraments['html'];
    } else if ($style == 'text') {
        if ($paraments['link'] == "" || $paraments['title'] == "") {
            return false;
        }
        $code = '<a href="' . $paraments['link'] . '" target="_blank"';
        if ($paraments['size'] != "") {
            $code .= 'style="font-size: ' . $paraments['size'] . 'px"';
        }
        $code .= '>' . $paraments['title'] . '</a>';
    } else if ($style == 'image') {
        if ($paraments['link'] == "" || $paraments['url'] == "") {
            return false;
        }
        $pre = AvatarUploader::getDataDir();
        $code = '<a href="' . $paraments['link'] . '" target="_blank"><img src="http://' . (isset($INI['pre']['url']) ? $INI['pre']['url'] : $INI['web']) . '/data/' . $pre . '/ad/' . strtolower($paraments['url']) . '"';
        $code .= $paraments['height'] ? ' height="' . $paraments['height'] . '"' : '';
        $code .= $paraments['width'] ? ' width="' . $paraments['width'] . '"' : '';
        $code .= $paraments['alt'] ? ' alt="' . $paraments['alt'] . '"' : '';
        $code .= ' border="0"></a>';
    } else if ($style == 'flash') {
        if ($paraments['url'] == "" || $paraments['height'] == "" || $paraments['width'] == "") {
            return false;
        }
        $code = '<embed width="' . $paraments['width'] . '" height="' . $paraments['heigt'] . '" src="' . $paraments['url'] . '" type="application/x-shockwave-flash" wmode="transparent"></embed>';
    } else {
        return false;
    }
    return $code;
}

//输出广告代码
function adshow($type) {
    global $INI;
    $result = array();
    if ($INI['pre']['adv_mode'] == 1 || $INI['pre']['adv_mode'] == 2) {

        $Connection = DB::GetDbId();
        $res = @mysql_query("SELECT * FROM ad WHERE type='$type' AND available=1", $Connection);

        if ($res) {
            while ($row = mysql_fetch_assoc($res)) {
                $row = array_change_key_case($row, CASE_LOWER);
                if ($one) {
                    $result = $row;
                    break;
                } else {
                    array_push($result, $row);
                }
            }

            @mysql_free_result($res);
        }
        if ($INI['pre']['adv_mode'] == 2) {
            $num = array_rand($result);
            $code = $result[$num]['codes'];
            return $code;
        }
    }
    $condition = array('type' => $type,
        'available' => '1',
    );
    $results = DB::LimitQuery('ad', array('condition' => $condition,
    ));
    $results = Config::MergeINI($result, $results);
    $num = array_rand($results);
    $code = $results[$num]['codes'];
    return $code;
}

//友情链接
function links() {
    global $INI;
    $results = DB::LimitQuery('link', array('condition' => array('stat' => 1,),
                'order' => 'ORDER BY displayorder ASC',));
    /*
      foreach ($results AS $key => $one) {
      $pre = AvatarUploader::getDataDir();
      $results[$key]['logo'] = $one['logo'] ? '/data/' . $pre . '/ad/' . $one['logo'] : '';
      }


      foreach ($results AS $key => $one) {
      if ($one['name'] == "" && $one['logo'] == "") {
      $results[$key]['style'] = 'style="width:0%"';
      } elseif ($one['name'] != "" && $one['logo'] != "") {
      if ($one['description'] == "")
      $results[$key]['style'] = 'style="width:12.4%"';
      }else {
      $results[$key]['description'] = "";
      $results[$key]['style'] = 'style="width:12.4%"';
      }
      }
     */
    return $results;
}

?>