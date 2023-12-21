<?php

const YEAR = 2023;
const DAY = 6;
$input = getInput(YEAR, DAY);
$races = extractOutput($input);
$totals = [];
/**
 * @var Race $race
 */
foreach ($races as $race) {
    $boat = new Boat();
    $totals[] = count ($boat->calculate($race->time, $race->distance));
}

var_dump(array_product($totals));
exit;



class Race
{
    public function __construct(
        public $time,
        public $distance
    )
    {
    }
}


class Boat
{
    public function __construct(
        public $speed = 0
    )
    {
    }

    public function charge($charge)
    {
        $this->speed = $charge;
    }

    public function calculateDistanceOverSpeed($distance)
    {
        return $this->speed * $distance;
    }

    public function calculate($time, $distance) {

        $results = [];
        for ($speed = 0; $speed <= $time; $speed++) {
            $timeLeftAfterCharge = $time - $speed;

            $distanceCovered = 0;

            for ($t = 0; $t < $timeLeftAfterCharge; $t++) {
                $distanceCovered += $speed;
            }

            if ($distanceCovered > $distance) {
                $results[] = [
                    'speed' => $speed,
                    'distance' => $distanceCovered
                ];
            }
        }

        return $results;
    }
}

function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}

function extractOutput($input): array {

    foreach ($input as $line) {
        if (str_contains($line, 'Time:')) {
            $times = array_filter(explode(' ', trim (
                explode(':', $line)[1]
            )));

            $times = array_values($times);
        }
        else if (str_contains($line, 'Distance:')) {
            $distances = array_filter(explode(' ', trim (
                explode(':', $line)[1]
            )));

            $distances = array_values($distances);
        }
    }

    $races = [];
    foreach ($times as $k => $time) {
        $races[] = new Race((int) $times[$k], (int) $distances[$k]);
    }

    return $races;
}
