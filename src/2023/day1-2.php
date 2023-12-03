<?php

const YEAR = 2023;
const DAY = 1;

const DIGITS = [
    '1' => 1,
    '2' => 2,
    '3' => 3,
    '4' => 4,
    '5' => 5,
    '6' => 6,
    '7' => 7,
    '8' => 8,
    '9' => 9,
    'one' => 1,
    'two' => 2,
    'three' => 3,
    'four' => 4,
    'five' => 5,
    'six' => 6,
    'seven' => 7,
    'eight' => 8,
    'nine' => 9
];


$input = getInput(YEAR, DAY);

$result = 0;
foreach ($input as $k => $message) {

    $x = getDigitPositions($message);
    var_dump($k . ': ' . $x);
    $result += getDigitPositions($message);
}

### RESULT ###
var_dump($result);

function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}


function getDigitPositions(string $input): int {
    $positions = [];
    $lastPos = 0;
    foreach (DIGITS as $k => $digit) {
        while (($lastPos = strpos($input, $k, $lastPos))!== false) {
            $positions[$lastPos] = $digit;
            $lastPos = $lastPos + strlen($k);
        }
    }

    ksort($positions);

    return (int) (reset($positions) . end($positions));
}

function getFirstAndLastDigit(string $input): int {
    $test = str_split(
        filter_var($input, FILTER_SANITIZE_NUMBER_INT)
    );

    return (int) (reset($test) . end($test));
}