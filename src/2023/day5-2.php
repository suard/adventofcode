<?php


ini_set('memory_limit', '2G');

const YEAR = 2023;
const DAY = 5;

$seeds = [];
$soils = [];
$results = [];
$input = getInput(YEAR, DAY);
// extractInput($input, $seeds, $soils);


function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}

$parts = [];
foreach ($input as $line) {

    if (empty($line)) {
        // split
        $parts[] = $current;
        $current = [];
    } else {
        $current[] = $line;
    }
}

$seedsLine = array_shift($parts)[0];
$seeds = explode(
    ' ',
    substr($seedsLine, 7)
);

$parts[] = $current;

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


$seedRanges = getSeedRanges($seeds);


var_dump(mergeRanges($seedRanges));
exit;

$location = PHP_INT_MAX;
for ($i = 0; $i < count($seeds); $i+=2) {
    $totals = [];
    $min = (int) $seeds[$i];
    $max = (int) $seeds[$i] + $seeds[$i+1];


    $resultMin = calculateLocation($min, $soils);
    $resultMax = calculateLocation($max, $soils);

    var_dump($resultMin, $resultMax, 'x');

    for ($m = $min; $m < $max; $m++) {
        $total= calculateLocation($m, $soils);


        //$totals[] = $total;

        if ($total < $location) {
            var_dump($total);
            $location = $total;
            $totals[] = $total;
        }
    }
}



function getSeedRanges($seeds) {

    for ($i = 0; $i < count($seeds); $i+=2) {
        $min = (int) $seeds[$i];
        $max = (int) $seeds[$i] + $seeds[$i+1];

        $seedRanges[] = ['start' => $min, 'end' => $max];
    }


    return $seedRanges;
}

function mergeRanges($ranges) {
    // Sort ranges based on the start values
    usort($ranges, function ($a, $b) {
        return $a['start'] <=> $b['start'];
    });

    $mergedRanges = [];

    foreach ($ranges as $range) {
        // If the mergedRanges array is empty or the current range does not overlap with the last one, add it to the result
        if (empty($mergedRanges) || $range['start'] > $mergedRanges[count($mergedRanges) - 1]['end']) {
            $mergedRanges[] = $range;
        } else {
            // If the current range overlaps with the last one, merge them
            $mergedRanges[count($mergedRanges) - 1]['end'] = max($mergedRanges[count($mergedRanges) - 1]['end'], $range['end']);
        }
    }

    return $mergedRanges;
}


class Seed {
    public function __construct(
        public int $id
    )
    {
    }
}

function calculateLocation(int $seed, $soils) {

    $seed = new Seed($seed);
    $result = new Result($seed->id);
    $value = $seed->id;
    foreach ($soils as $soil) {
        $value = $soil->calculate($value);
        $result->addResult($soil->name, $value);
    }

    return $result->results['humidity-to-location map:'];
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