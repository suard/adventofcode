<?php

const YEAR = 2023;
const DAY = 1;

$input = getInput(YEAR, DAY);

$result = 0;
foreach ($input as $message) {
    $result += getFirstAndLastDigit($message);
}

### RESULT ###
var_dump($result);

function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}

function getFirstAndLastDigit(string $input): int {
    $test = str_split(
        filter_var($input, FILTER_SANITIZE_NUMBER_INT)
    );

    return (int) (reset($test) . end($test));
}