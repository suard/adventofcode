<?php

const YEAR = 2023;
const DAY = 3;

const SYMBOLS = [
    '*',
    '#',
    '+',
    '$',
    '@',
    '/',
    '=',
    '%',
    '-',
    '&'
];

$input = getInput(YEAR, DAY);

$grid = [];
foreach ($input as $k => $line) {
    $grid[$k] = str_split($line);
}

$positions = [];

foreach ($grid as $r => $row) {
    foreach ($row as $c => $value) {

        if (in_array($value, SYMBOLS)) {
            $positions = searchSurroundings($r, $c, $grid, $positions);
        }
    }
}

ksort($positions);

foreach ($positions as &$position) {
    ksort($position);
}

$results = [];

foreach ($grid as $r => $row) {
    $current = '';
    $store = false;
    foreach ($row as $c => $value) {
        if (is_numeric($value)) {
            $current .= $value;

            if (isset($positions[$r][$c])) {
                $store = true;
            }
        } else {
            if ($store === true) {
                $results[] = (int) $current;

            }
            $current = '';
            $store = false;
        }
    }

    if (!empty($current && $store === true)) {
        $results[] = (int) $current;
    }
}

var_dump(array_sum($results));






function searchSurroundings($r, $c, $grid, $results) {

    $pos[] = ['x' => $r-1, 'y' => $c-1];
    $pos[] = ['x' => $r-1, 'y' => $c];
    $pos[] = ['x' => $r-1, 'y' => $c+1];

    $pos[] = ['x' => $r, 'y' => $c-1];
    $pos[] = ['x' => $r, 'y' => $c+1];

    $pos[] = ['x' => $r+1, 'y' => $c-1];
    $pos[] = ['x' => $r+1, 'y' => $c];
    $pos[] = ['x' => $r+1, 'y' => $c+1];

    foreach ($pos as $p) {
        $x = $p['x'];
        $y = $p['y'];
        if (isset($grid[$x][$y])) {
            if (is_numeric($grid[$x][$y])) {
                $results[$x][$y] = $grid[$x][$y];
            }
        }
    }

    return $results;
}


function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}