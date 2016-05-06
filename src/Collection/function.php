<?php

namespace Ardent\Collection;


function negate(callable $f) {
    return function () use ($f) {
        return !call_user_func_array($f, func_get_args());
    };
}


/**
 * @param $a
 * @param $b
 * @return int
 */
function compare($a, $b) {
    if ($a < $b) {
        return -1;
    } elseif ($b < $a) {
        return 1;
    } else {
        return 0;
    }
}


/**
 * @param $a
 * @param $b
 * @return bool
 */
function equal($a, $b) {
    return $a == $b;
}


/**
 * @param $a
 * @param $b
 * @return bool
 */
function same($a, $b) {
    return $a === $b;
}


/**
 * @param $item
 *
 * @return string
 */
function hash($item) {
    if (is_object($item)) {
        return spl_object_hash($item);
    } elseif (is_numeric($item) || is_bool($item)) {
        return "s_" . intval($item);
    } elseif (is_string($item)) {
        return "s_$item";
    } elseif (is_resource($item)) {
        return "r_$item";
    } elseif (is_array($item)) {
        return 'a_' . md5(serialize($item));
    }

    return '0';
}
