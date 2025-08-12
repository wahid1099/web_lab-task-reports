<?php


$books = [
     "Sajjad" => 24,
    "zarir" => 30,
    "sadman" => 42,
    "wahid" => 35,
    "tabib" => 28,
];

function gcd($a, $b) {
    while ($b != 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a;
}

$gcdPairs = [];
$authors = array_keys($books);
$counts = array_values($books);

for ($i = 0; $i < count($books); $i++) {
    for ($j = $i + 1; $j < count($books); $j++) {
        $g = gcd($counts[$i], $counts[$j]);
        $pair = "{$authors[$i]} & {$authors[$j]}";
        $gcdPairs[$pair] = $g;
    }
}

$uniqueGcds = array_unique(array_values($gcdPairs));
rsort($uniqueGcds);

$secondLargestGcd = $uniqueGcds[1] ?? null;

echo "<pre>";
if ($secondLargestGcd === null) {
    echo "Not enough unique GCDs to find the second largest.\n";
} else {
    echo "Second largest GCD: $secondLargestGcd\n";
    echo "Author pairs with this GCD:\n";
    foreach ($gcdPairs as $pair => $gcdValue) {
        if ($gcdValue === $secondLargestGcd) {
            echo "- $pair\n";
        }
    }
}
echo "</pre>";
?>
