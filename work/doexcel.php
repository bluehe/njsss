<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$t = $_REQUEST['t'];
if ($t == 'work_order_list') {
    $by = $_REQUEST['by'] ? $_REQUEST['by'] : 'id';
    $sort = $_REQUEST['sort'] ? $_REQUEST['sort'] : 'desc';

    $condition = array();
    if (isset($_REQUEST['id'])) {
        $ids = explode(',', $_REQUEST['id']);
        $condition['id'] = $ids;
    } else {
        $checkin = $_REQUEST['checkin'];
        $checkout = $_REQUEST['checkout'];
        $bid = $_REQUEST['bid'];
        $pe = $_REQUEST['pe'];
        $depart = $_REQUEST['depart'];
        if ($checkin) {
            $in = explode(' ', $checkin);
            $condition[] = "check_in>= '$in[0]' and check_in<= '$in[2]'";
        }
        if ($checkout) {
            $out = explode(' ', $checkout);
            $condition[] = "check_out>= '$out[0]' and check_out<= '$out[2]'";
        }
        if ($bid) {
            $condition[] = "FIND_IN_SET('$bid',bid)";
        }
        if ($pe) {
            $condition[] = "person LIKE '%{$pe}%'";
        }
        if ($depart) {
            $condition[] = "depart LIKE '%{$depart}%'";
        }
    }
    $nums = DB::Count('order', $condition);
    $data = DB::LimitQuery('order', array('condition' => $condition, 'order' => 'ORDER BY ' . $by . ' ' . $sort . ($by == 'id' ? '' : ',id desc'),));

    if ($data) {
        $titles = array('head' => '订单记录',
            'th' => array(array('value' => 'depart', 'name' => '部门', 'width' => 15, 'style' => array('br' => true)),
                array('value' => 'check_in', 'name' => '入住时间', 'width' => 12),
                array('value' => 'bed', 'name' => '床位', 'width' => 21, 'style' => array('br' => true)),
                array('value' => 'check_num', 'name' => '人数', 'width' => 6),
                array('value' => 'per', 'name' => '人员', 'width' => 56, 'style' => array('br' => true)),
                array('value' => 'key_num', 'name' => '钥匙', 'width' => 6),
                array('value' => 'deposit', 'name' => '押金', 'width' => 10),
                array('value' => 'check_leave', 'name' => '预定退房', 'width' => 12),
                array('value' => 'note', 'name' => '入住备注', 'width' => 15, 'style' => array('br' => true)),
                array('value' => 'check_out', 'name' => '退房时间', 'width' => 12),
                array('value' => 'deposit_out', 'name' => '退还押金', 'width' => 10),
                array('value' => 'charge', 'name' => '房费', 'width' => 10),
                array('value' => 'income', 'name' => '其他费用', 'width' => 10),
                array('value' => 'reason', 'name' => '退房原因', 'width' => 15, 'style' => array('br' => true)),
                array('value' => 'checkout_note', 'name' => '退房备注', 'width' => 15, 'style' => array('br' => true)),
        ));
        //楼层
        $floornames = DB::GetDbColumn('parameter', array('k', 'v'), array('name' => 'floorname'), true);
        //楼栋
        $forums = DB::GetDbColumn('forum', array('id', 'fid', 'mold', 'name'), array());
        foreach ($data as $key => $one) {

            $beds = DB::LimitQuery('bed', array('condition' => array('id' => explode(',', $one['bid'])), 'order' => "order by sroom asc,bed asc"));
            foreach ($beds as $k => $bed) {
                if ($k > 0) {
                    $data[$key]['bed'] .= chr(10);
                }
                $data[$key]['bed'] .= $forums[$bed[fid]][name] . $floornames[$bed[floor]] . $bed[broom] . ($forums[$bed[fid]][mold] == 'mul' ? '-' . $bed[sroom] : '') . '-' . $bed[bed];
            }
            if (count($beds) > 1) {
                $data[$key]['row_height'] = 15 * count($beds);
            }

            $persons = json_decode($one['person'], true);
            foreach ($persons as $k => $person) {
                if ($k > 0) {
                    $data[$key]['per'] .= chr(10);
                }
                $data[$key]['per'] .= '❤:' . $person[name] . '  ☎:' . $person[tel] . ($person[idcard] ? '  ♟:' . $person[idcard] : '');
            }
            if (count($persons) > count($beds)) {
                $data[$key]['row_height'] = 15 * count($persons);
            }
            if ($one['check_leave']) {
                $data[$key]['check_leave'] = date('Y-m-d', $one['check_leave']);
            }
        }

        include_once(dirname(__FILE__) . '/excel_list.php');
    } else {
        showmessage('error', '', '不存在相关内容！');
        Utility::Redirect();
    }
} else {
    showmessage('error', '', '不存在相关内容！');
    Utility::Redirect();
}
?>