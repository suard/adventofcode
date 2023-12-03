<?php

const YEAR = 2023;
const DAY = 3;

const SYMBOLS = [
    '*',
];



$input = getInput(YEAR, DAY);


$grid = [];
foreach ($input as $k => $line) {
    $grid[$k] = str_split($line);
}

$total = 0;
foreach ($grid as $r => $row) {
    foreach ($row as $c => $value) {
        if (in_array($value, SYMBOLS)) {

            $gear = new Gear($r, $c, $grid);
            $result = $gear->find();

            $gearResult = $gear->fetchNumbers($result);

            if (count($gearResult) === 2) {
                $total += ($gearResult[0] * $gearResult[1]);
            }
        }
    }
}


var_dump($total);

function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}


class Gear
{
    public function __construct(
        public int $x,
        public int $y,
        public array $grid
    ) {}

    function find() {

        $pos[] = ['x' => $this->x-1, 'y' => $this->y-1];
        $pos[] = ['x' => $this->x-1, 'y' => $this->y];
        $pos[] = ['x' => $this->x-1, 'y' => $this->y+1];

        $pos[] = ['x' => $this->x, 'y' => $this->y-1];
        $pos[] = ['x' => $this->x, 'y' => $this->y+1];

        $pos[] = ['x' => $this->x+1, 'y' => $this->y-1];
        $pos[] = ['x' => $this->x+1, 'y' => $this->y];
        $pos[] = ['x' => $this->x+1, 'y' => $this->y+1];

        foreach ($pos as $p) {
            $x = $p['x'];
            $y = $p['y'];
            if (isset($this->grid[$x][$y])) {
                if (is_numeric($this->grid[$x][$y])) {
                    $results[$x][$y] = $this->grid[$x][$y];
                }
            }
        }

        return $results;
    }

    function fetchNumbers(array $rows)
    {
        $results = [];

        foreach ($rows as $r => $_) {

            $row = $this->grid[$r];
            $current = '';
            $store = false;

            foreach ($row as $c => $value) {

                if (is_numeric($value)) {
                    $current .= $value;

                    if (isset($rows[$r][$c])) {
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

        return $results;
    }
}