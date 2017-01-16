<?php

/**
 * @package JiWai.de
 * @copyright AKA Inc.
 * @author shwdai@gmail.com
 */
class DB {

    static private $mInstance = null;
    static private $mConnection = null;
    static public $mDebug = false;
    static public $mError = null;
    static private $mPre = null;

    static public function &Instance() {
        if (null == self::$mInstance) {
            $class = __CLASS__;
            self::$mInstance = new $class;
        }
        return self::$mInstance;
    }

    function __construct() {
        global $INI;
        $config = Config::Instance('php');
        $host = (string) $config['db']['host'];
        $user = (string) $config['db']['user'];
        $pass = (string) $config['db']['pass'];
        $name = (string) $config['db']['name'];
        self::$mPre = (string) $config['db']['pre'];

        self::$mConnection = mysqli_connect($host, $user, $pass, $name);
        if (mysqli_errno()) {
            throw new Exception("Connect failed: " . mysqli_error());
            exit;
        }
        // @mysql_select_db($name, self::$mConnection);
        @mysqli_query(self::$mConnection, "SET NAMES UTF8;");
    }

    function GetDbId() {

        $config = Config::Instance('php');
        $host = (string) $config['db']['host'];
        $user = (string) $config['db']['user'];
        $pass = (string) $config['db']['pass'];
        $name = (string) $config['db']['name'];
        self::$mPre = (string) $config['db']['pre'];


        $Connection = @mysqli_connect($host, $user, $pass, $name);

        if (mysqli_errno())
            throw new Exception("Connect failed: " . mysqli_error());

        @mysqli_select_db($name, $Connection);
        @mysqli_query($Connection, "SET NAMES UTF8;");
        return $Connection;
    }

    static function GetLinkId() {
        self::Instance();
        return self::$mConnection;
    }

    function __destruct() {
        self::Close();
    }

    static public function Debug() {
        self::$mDebug = !self::$mDebug;
    }

    static public function Close() {
        if (is_resource(self::$mConnection)) {
            @mysqli_close(self::$mConnection);
        }

        self::$mConnection = null;
        self::$mInstance = null;
    }

    static public function EscapeString($string) {
        self::Instance();
        return @mysqli_real_escape_string(self::$mConnection, $string);
    }

    static public function GetInsertId() {
        self::Instance();
        return intval(@mysqli_insert_id(self::$mConnection));
    }

    static public function Query($sql) {
        self::Instance();
        $sql = str_replace('{{mPre}}', self::$mPre, $sql);
        if (self::$mDebug) {
            echo $sql;
        }

        $result = @mysqli_query(self::$mConnection, $sql);

        if ($result) {
            return $result;
        } else {
            self::$mError = mysqli_error();
        }

        self::Close();
        return false;
    }

    static public function GetTableRow($table, $condition) {
        return self::LimitQuery($table, array(
                    'condition' => $condition,
                    'one' => true,
        ));
    }

    static public function GetDbRowById($table, $ids = array()) {
        $one = is_array($ids) ? false : true;
        settype($ids, 'array');
        $idstring = join('\',\'', $ids);
        $q = "SELECT * FROM `" . self::$mPre . "{$table}` WHERE id IN ('{$idstring}')";
        $r = self::GetQueryResult($q, $one);
        if ($one)
            return $r;
        return Utility::AssColumn($r, 'id');
    }

    static public function GetDbColumn($table, $columns = 'id', $condition = array(), $n = false, $d = true) {
        $one = is_array($columns) ? false : true;
        if (!$one) {
            $num = count($columns);
        }
        if ($one) {
            $rs = self::LimitQuery($table, array('condition' => $condition));
        } else {
            $rs = self::LimitQuery($table, array('condition' => $condition, 'order' => 'ORDER BY ' . $columns[0]));
        }
        $ret = array();
        foreach ($rs as $key => $r) {
            if ($one) {
                $ret[] = $r[$columns];
            } else {
                if ($num == 2 && $n) {
                    $ret[$r[$columns[0]]] = $r[$columns[1]];
                } else {
                    foreach ($columns as $column) {
                        if ($d) {
                            $ret[$r[$columns[0]]][$column] = $r[$column];
                        } else {
                            $ret[$key][$column] = $r[$column];
                        }
                    }
                }
            }
        }
        return $ret;
    }

    static public function LimitQuery($table, $options = array()) {
        $condition = isset($options['condition']) ? $options['condition'] : null;
        $one = isset($options['one']) ? $options['one'] : false;
        $offset = isset($options['offset']) ? abs(intval($options['offset'])) : 0;
        if ($one) {
            $size = 1;
        } else {
            $size = isset($options['size']) ? abs(intval($options['size'])) : null;
        }
        $select = isset($options['select']) ? $options['select'] : '*';
        $group = isset($options['group']) ? $options['group'] : null;
        $order = isset($options['order']) ? $options['order'] : null;
        $cache = isset($options['cache']) ? abs(intval($options['cache'])) : 0;

        $condition = self::BuildCondition($condition);
        $condition = (null == $condition) ? null : "WHERE $condition";

        $limitation = $size ? "LIMIT $offset,$size" : null;

        $sql = "SELECT {$select} FROM `" . self::$mPre . "$table` $condition $group $order $limitation";
        return self::GetQueryResult($sql, $one, $cache);
    }

    static public function GetQueryResult($sql, $one = true, $cache = 0) {
        $mkey = Cache::GetStringKey($sql);
        if ($cache > 0) {
            $ret = Cache::Get($mkey);
            if ($ret)
                return $ret;
        }

        $ret = array();
        $result = self::Query($sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row = array_change_key_case($row, CASE_LOWER);
                if ($one) {
                    $ret = $row;
                    break;
                } else {
                    array_push($ret, $row);
                }
            }

            @mysqli_free_result($result);
        }
        if ($cache > 0 && $ret) {
            Cache::Set($mkey, $ret, 0, $cache);
        }

        return $ret;
    }

    static public function SaveTableRow($table, $condition) {
        return self::Insert($table, $condition);
    }

    static public function Insert($table, $condition) {
        self::Instance();

        $sql = "INSERT INTO `" . self::$mPre . "$table` SET ";
        $content = null;

        foreach ($condition as $k => $v) {
            $v_str = null;
            /* if ( is_string($v) )
              $v_str = "'" . self::EscapeString($v) . "'";
              else if ( is_numeric($v) )
              $v_str = $v;
              else */if (is_null($v))
                $v_str = 'NULL';
            else
                $v_str = "'" . self::EscapeString($v) . "'";

            $content .= "`$k`=$v_str,";
        }

        $content = trim($content, ',');
        $sql .= $content;
        $result = self::Query($sql);
        if (false == $result) {
            self::Close();
            return false;
        }
        ($insert_id = self::GetInsertId()) || ($insert_id = true);
        return $insert_id;
    }

    static public function DelTableRow($table, $id = null, $k = 'id') {
        settype($id, 'array');
        $idstring = join('\',\'', $id);
        $sql = "DELETE FROM `" . self::$mPre . "$table` WHERE `{$k}` IN('$idstring')";
        self::Query($sql);
        if ($k != 'id')
            return true;
        Cache::ClearObject($table, $id);
        return true;
    }

    static public function Delete($table = null, $condition = array()) {
        if (null == $table || empty($condition))
            return false;
        self::Instance();

        $condition = self::BuildCondition($condition);
        $condition = (null == $condition) ? null : "WHERE $condition";
        $sql = "DELETE FROM `" . self::$mPre . "$table` $condition";
        return DB::Query($sql);
    }

    static public function Update($table = null, $id = 1, $updaterow = array(), $pkname = 'id') {

        if (null == $table || empty($updaterow) || null == $id)
            return false;

        if (is_array($id))
            $condition = self::BuildCondition($id);
        else
            $condition = "`$pkname`='$id'";

        self::Instance();

        $sql = "UPDATE `" . self::$mPre . "$table` SET ";
        $content = null;

        foreach ($updaterow as $k => $v) {
            $v_str = null;
            /* if ( is_string($v) )
              $v_str = "'" . self::EscapeString($v) . "'";
              else if ( is_numeric($v) )
              $v_str = $v;
              else */if (is_null($v))
                $v_str = 'NULL';
            else if (is_array($v))
                $v_str = $v[0]; //for plus/sub/multi;
            else
                $v_str = "'" . self::EscapeString($v) . "'";

            $content .= "`$k`=$v_str,";
        }

        $content = trim($content, ',');
        $sql .= $content;
        $sql .= " WHERE $condition";
        $result = self::Query($sql);

        if (false == $result) {
            self::Close();
            return false;
        }

        return true;
    }

    static public function GetField($table, $select_map = array()) {
        $fields = array();
        $q = self::Query("DESC `" . self::$mPre . "$table`");

        while ($r = mysqli_fetch_assoc($q)) {
            $Field = $r['Field'];
            $Type = $r['Type'];

            $type = 'varchar';
            $cate = 'other';
            $extra = null;

            if (preg_match('/^id$/i', $Field))
                $cate = 'id';
            else if (preg_match('/^_time/i', $Field))
                $cate = 'integer';
            else if (preg_match('/^_number/i', $Field))
                $cate = 'integer';
            else if (preg_match('/_id$/i', $Field))
                $cate = 'fkey';


            if (preg_match('/text/i', $Type)) {
                $type = 'text';
                $cate = 'text';
            }
            if (preg_match('/date/i', $Type)) {
                $type = 'date';
                $cate = 'time';
            } else if (preg_match('/int/i', $Type)) {
                $type = 'int';
            } else if (preg_match('/(enum|set)\((.+)\)/i', $Type, $matches)) {
                $type = strtolower($matches[1]);
                eval("\$extra=array($matches[2]);");
                $extra = array_combine($extra, $extra);

                foreach ($extra AS $k => $v) {
                    $extra[$k] = isset($select_map[$k]) ? $select_map[$k] : $v;
                }
                $cate = 'select';
            }

            $fields[] = array(
                'name' => $Field,
                'type' => $type,
                'extra' => $extra,
                'cate' => $cate,
            );
        }
        return $fields;
    }

    static public function Exist($table, $condition = array()) {
        $row = self::LimitQuery($table, array(
                    'condition' => $condition,
                    'one' => true,
        ));

        return empty($row) ? false : (isset($row['id']) ? $row['id'] : true);
    }

    static public function BuildCondition($condition = array(), $logic = 'AND') {
        if (is_string($condition) || is_null($condition))
            return $condition;

        $logic = strtoupper($logic);
        $content = null;
        foreach ($condition as $k => $v) {
            $v_str = null;
            $v_connect = '=';

            if (is_numeric($k)) {
                $content .= $logic . ' (' . self::BuildCondition($v, $logic) . ')';
                continue;
            }

            $maybe_logic = strtoupper($k);
            if (in_array($maybe_logic, array('AND', 'OR'))) {
                $content .= $logic . ' (' . self::BuildCondition($v, $maybe_logic) . ')';
                continue;
            }

            /* if ( is_numeric($v) ) {
              $v_str = $v;
              }
              else */if (is_null($v)) {
                $v_connect = ' IS ';
                $v_str = ' NULL';
            } else if (is_array($v)) {
                if (isset($v[0])) {
                    $v_str = null;
                    foreach ($v AS $one) {
                        /* if (is_numeric($one)) {
                          $v_str .= ','.$one;
                          } else { */
                        $v_str .= ',\'' . self::EscapeString($one) . '\'';
                        //}
                    }
                    $v_str = '(' . trim($v_str, ',') . ')';
                    if (in_array('NOT', $v)) {
                        $v_connect = 'NOT IN';
                    } else {
                        $v_connect = 'IN';
                    }
                } else if (empty($v)) {
                    $v_str = $k;
                    $v_connect = '<>';
                } else {
                    $v_connect = array_shift(array_keys($v));
                    $v_s = array_shift(array_values($v));
                    $v_str = "'" . self::EscapeString($v_s) . "'";
                    $v_str = is_numeric($v_s) ? $v_s : $v_str;
                }
            } else {
                $v_str = "'" . self::EscapeString($v) . "'";
            }

            $content .= " $logic `$k` $v_connect $v_str ";
        }

        $content = preg_replace('/^\s*' . $logic . '\s*/', '', $content);
        $content = preg_replace('/\s*' . $logic . '\s*$/', '', $content);
        $content = trim($content);

        return $content;
    }

    static public function CheckInt($id) {
        $id = intval($id);

        if (0 >= $id)
            throw new Exception('must int!');

        return $id;
    }

    static public function Count($table, $condition = null, $sum = null, $group = null) {
        $condition = self::BuildCondition($condition);
        $condition = null == $condition ? null : "WHERE $condition";
        $zone = $sum ? "SUM({$sum})" : "COUNT(1)";
        $group = $group ? $group : '';
        $sql = "SELECT {$zone} AS count FROM `" . self::$mPre . "$table` $condition $group";
        $row = DB::GetQueryResult($sql, true);
        return $sum ? (0 + $row['count']) : intval($row['count']);
    }

}

?>
