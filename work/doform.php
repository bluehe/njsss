<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$form_type = $_POST['form_type'];
if ($form_type == 'order') {
//订单信息
    $id = $_POST['id'];
    $order = $_POST['order'];
    $person = $_POST['person'];
    $bid = $_POST['bid'];

    if ($order['stat'] == '') {
        echo 'error^$^请选择入住类型！';
        exit;
    }
    if (count($bid) == 0) {
        echo 'error^$^请选择入住床位！';
        exit;
    }
    if ($order['key_num'] == '') {
        echo 'error^$^请输入钥匙数量！';
        exit;
    }
    if ($order['deposit'] == '') {
        echo 'error^$^请输入入住押金！';
        exit;
    }
    if ($order['check_in'] == '') {
        echo 'error^$^请选择入住日期！';
        exit;
    }
    if ($order['check_num'] == '') {
        echo 'error^$^请输入入住人数！';
        exit;
    }
    if (isset($person)) {
        foreach ($person as $p) {
            if ($p['name'] == '') {
                echo 'error^$^请输入人员姓名！';
                exit;
            }
            if ($p['tel'] == '') {
                echo 'error^$^请输入联系电话！';
                exit;
            }
        }
    } else {
        echo 'error^$^请输入至少一个人员信息！';
        exit;
    }

    //处理数据
    $order['person'] = json_encode($person);
    $bed = DB::GetTableRow('bed', array('id' => $id));
    $u = DB::GetTableRow('order', array('id' => $bed['order_id']));

    if ($u) {
        $order['updated_at'] = time();
        $order['updated_uid'] = $login_user_id;
        $change = array_diff_assoc($order, $u);
        if (!empty($change)) {
            $res = DB::Update('order', $bed['order_id'], $change, 'id');
            $bed_id = DB::GetDbColumn('bed', 'id', array('order_id' => $bed['order_id']));
            if (!empty(array_diff_assoc($bed_id, $bid)) || !empty(array_diff_assoc($bid, $bed_id))) {
                $res = $res && DB::Update('bed', array('id' => $bed_id), array('order_id' => NULL));
                $res = $res && DB::Update('bed', array('id' => $bid), array('order_id' => $bed['order_id']));
            }

            if ($res) {
                showmessage('success', '', '信息修改成功！');
                echo 'redirect^$^' . WEB_ROOT . '/work/order?id=' . $bid[0];
            } else {
                echo 'error^$^信息修改失败！';
            }
        }
    } else {
        $order['checkin_uid'] = $login_user_id;
        $order['created_uid'] = $login_user_id;
        $order['created_at'] = time();
        $order_id = DB::Insert('order', $order);
        if ($order_id) {
            $result = DB::Update('bed', array('id' => $bid), array('order_id' => $order_id));
            if ($result) {
                showmessage('success', '', '入住成功！');
                echo 'redirect^$^' . WEB_ROOT . '/work/order?id=' . $bid[0];
            } else {
                DB::DelTableRow('order', $order_id);
                echo 'error^$^入住失败！';
            }
        } else {
            echo 'error^$^入住失败！';
        }
    }
} else {
    echo 'error|错误操作！';
}
?>