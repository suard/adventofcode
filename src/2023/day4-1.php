<?php

const YEAR = 2023;
const DAY = 4;

$input = getInput(YEAR, DAY);

$games = [];
$total = 0;
foreach ($input as $k => $line) {
    $game = formatGame($line);
    $total += $game->getPoints();
}

var_dump($total);



function getInput(int $year, int $day) {
    return explode (
        PHP_EOL,
        file_get_contents(sprintf(__DIR__ .'/../../input/%s/day%s', $year, $day))
    );
}

function formatGame($input): Game {
    $gameNumber = (int) filter_var(
        strtok($input, ':'),
        FILTER_SANITIZE_NUMBER_INT
    );

    $numbers =
        explode(
            '|',
            explode(':', $input)[1]
        );


    $chosenCards = array_filter(explode(' ', $numbers[0]));
    $drawnCards = array_filter(explode(' ', $numbers[1]));

    return new Game(
        $gameNumber,
        $chosenCards,
        $drawnCards
    );
}


class Game
{
    public function __construct(
        public int $id,
        public array $chosenCards,
        public array $drawnCards
    )
    {
    }

    public function getWinningCards(): array
    {
        return array_intersect($this->drawnCards,$this->chosenCards);
    }

    public function getPoints(): int
    {
        $result = $this->getWinningCards();

        if (empty($result)) {
            return 0;
        }

        return 2 ** (count($result) - 1);
    }
}