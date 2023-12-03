<?php

const YEAR = 2023;
const DAY = 2;



$input = getInput(YEAR, DAY);

foreach ($input as $line) {
    $lines[] = extractOutput($line);
}

/**
 * @var Guess $guess
 */
$power = 0;
foreach ($lines as $guess) {

    $power += $guess->getMin('red') * $guess->getMin('blue') * $guess->getMin('green');

}

var_dump($power);
exit;

$total = 0;
foreach ($lines as $guess) {

    $isValid = true;
    /**
     * @var $bag Bag
     *
     */
    foreach ($guess as $bag) {
        if ($bag->blueIsValid() === false || $bag->greenIsValid() === false || $bag->redIsValid() === false) {
            $isValid = false;
        }
    }

    if ($isValid === true) {
        $total += $bag->id;
    }
}

### result
var_dump($total);


function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}

function extractOutput(string $line) {
    $exp = explode(':', $line);

    $id = (int) filter_var($exp[0], FILTER_SANITIZE_NUMBER_INT);

    $results = explode(';', $exp[1]);

    $bags = [];
    foreach ($results as $result) {
        $rgb = extractRBG($result);
        $bags[] = new Bag(id: $id, red: $rgb['red'], green: $rgb['green'], blue: $rgb['blue']);
    }

    $guess = new Guess($bags);
    return $guess;
}

function extractRBG($input): array {

    $result = [
        'red' => 0,
        'blue' => 0,
        'green' => 0
    ];

    $expl = explode(',', $input);

    foreach ($expl as $color) {

        if (str_contains($color, 'red')) {
            $result['red'] = (int) filter_var($color, FILTER_SANITIZE_NUMBER_INT);
        }

        if (str_contains($color, 'blue')) {
            $result['blue'] = (int) filter_var($color, FILTER_SANITIZE_NUMBER_INT);
        }

        if (str_contains($color, 'green')) {
            $result['green'] = (int) filter_var($color, FILTER_SANITIZE_NUMBER_INT);
        }
    }

    return $result;
}


class Guess {
    public function __construct(
        public array $bags
    ) {}

    public function getMin(string $color): int {
        $val = 0;
        foreach ($this->bags as $bag) {
            if ($bag->$color > $val) {
                $val = $bag->$color;
            }
        }

        return $val;
    }
}


class Bag
{
    const TRESHOLDS = [
        'red' => 12,
        'green' => 13,
        'blue' => 14
    ];

    public function __construct(
        public int $id,
        public int $red,
        public int $green,
        public int $blue
    ) {}

    public function redIsValid(): bool {

        if ($this->red > self::TRESHOLDS['red']) {
            return false;
        }

        return true;
    }


    public function blueIsValid(): bool {

        if ($this->blue > self::TRESHOLDS['blue']) {
            return false;
        }

        return true;
    }


    public function greenIsValid(): bool {

        if ($this->green > self::TRESHOLDS['green']) {
            return false;
        }

        return true;
    }
}