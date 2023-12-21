<?php

const YEAR = 2023;
const DAY = 5;

$seeds = [];
$soils = [];
$results = [];
$input = getInput(YEAR, DAY);
// extractInput($input, $seeds, $soils);

$parts = [];
$current = [];
foreach ($input as $line) {

    if (empty($line)) {
        // split
        $parts[] = $current;
        $current = [];
    } else {
        $current[] = $line;
    }
}

$parts[] = $current;

$seeds = extractSeeds(array_shift($parts)[0]);

foreach ($parts as $part) {
    $name = array_shift($part);

    $soil = new Soil($name);

    foreach ($part as $p) {

        $n = explode(' ', $p);
        $map = new Map (destination: (int) $n[0], source: (int) $n[1], range: (int) $n[2]);
        $soil->addMap($map);
    }

    $soils[] = $soil;
}

/**
 * @var Seed $seed
 * @VAR Map $map
 */
foreach ($seeds as $seed) {
    $result = new Result($seed->id);
    $value = $seed->id;
    foreach ($soils as $soil) {
        $value = $soil->calculate($value);
        $result->addResult($soil->name, $value);

    }

    $results[] = $result;
}


$location = PHP_INT_MAX;
foreach ($results as $result) {

    if ($result->results['humidity-to-location map:'] < $location) {
        $location = $result->results['humidity-to-location map:'];
    }
}

var_dump($location);
exit;



function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}

function extractSeeds($line): array
{
    $seeds = [];
    $seedNumbers = explode(
        ' ',
        substr($line, 7)
    );

    foreach ($seedNumbers as $seedNumber) {
        $seeds[] = new Seed($seedNumber);
    }

    return $seeds;
}

class Seed {
    public function __construct(
        public int $id
    )
    {
    }
}

class Soil {

    public function __construct(
        public string $name,
        public array $maps = []
    ) {
    }

    public function addMap(Map $map)
    {
        $this->maps[] = $map;
    }

    public function getMaps() {
        return $this->maps;
    }

    public function calculate(int $seed)
    {
        $result = null;
        foreach ($this->maps as $map) {
            $result = $map->process($seed);

            if ($result !== $seed) {
                return $result;
            }
        }



        return $result;
    }
}

class Map
{
    public function __construct(
        public int $destination,
        public int $source,
        public int $range
    )
    {
    }

    public function process(int $seed): ?int
    {

        $source = $this->getSource($seed);


        if ($source !== null) {
            return $this->destination + $source;
        }

        return $seed;


    }

    public function getSource(int $seed): ?int
    {
        $min = $this->source;
        $max = $this->source + $this->range;



        if ($seed >= $min && $seed < $max) {
            return $seed - $this->source;
        }

        return null;
    }

//    public function isDestination(int $seed): ?int
//    {
//        $min = $this->destination;
//        $max = $this->destination + $this->range;
//
//        if ($seed >= $min && $seed <= $max) {
//
//        }
//
//
//        return null;
//
//        return false;
//    }
}

class Result {
    public function __construct(
        public int $seed,
        public array $results = []
    )
    {
    }

    public function addResult(string $name, int $value)
    {
        $this->results[$name] = $value;
    }
}