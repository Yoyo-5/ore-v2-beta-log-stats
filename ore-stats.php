<?php

// Function to extract the difficulty value
function GetDifficulty($line) {
    $regex = "/\bdifficulty: (\d+)\b/";
    if (preg_match($regex, $line, $matches)) {
        $difficulty = $matches[1];
        // echo "Extracted difficulty: $difficulty\n";
        return $difficulty;
    }
    return null;
}

// Function to extract the balance
function GetBalance($line) {
    $regex = "/Stake balance: (\d+(\.\d+)?)/";
    if (preg_match($regex, $line, $matches)) {
        $balance = floatval($matches[1]);
        return $balance;
    }
    return null;
}

// Function to extract the reward between two hashes
function GetReward($line, $previousBalance) {
    $regex = "/Stake balance: (\d+(\.\d+)?)/";
    if (preg_match($regex, $line, $matches)) {
        $balance = floatval($matches[1]); // Balance from the second hash
        $reward = $balance - $previousBalance; // Calculate the reward
        return $reward;
    }
    return null;
}

// Path to the input text file
$inputFile = "output-ore-v2.log";

// Associative array to store difficulty frequencies
$difficultyCount = array();

// Associative array to store rewards by difficulty
$rewardsByDifficulty = array();

// Read the text file line by line and filter them
$lines = file($inputFile, FILE_IGNORE_NEW_LINES);
$previousBalance = 0;
$previousDifficulty = 0;
$totalRewards = 0;
$noRewardCount = 0;
$totalNumberOfHashes = 0;
foreach ($lines as $nb => $line) {
    $difficulty = GetDifficulty($line);
    $balance = GetBalance($line);
    $reward = GetReward($line, $previousBalance);
    if ($difficulty !== null) {
        $difficulty = (int)$difficulty;
        if (array_key_exists($difficulty, $difficultyCount)) {
            $difficultyCount[$difficulty]++;
        } else {
            $difficultyCount[$difficulty] = 1;
        }
        $previousDifficulty = $difficulty;
    }
    if ($balance !== null && $reward !== null && $reward > 0) {
        if (array_key_exists($previousDifficulty, $rewardsByDifficulty)) {
            $rewardsByDifficulty[$previousDifficulty][] = $reward;
        } else {
            $rewardsByDifficulty[$previousDifficulty] = array($reward);
        }
        $previousBalance = $balance;
    }
    if ($reward !== null && $reward == 0) {
        $noRewardCount++;}
    ($nb == count($lines) - 1) ? $totalRewards = $balance : null;
    $totalNumberOfHashes++;
}

// Display the results
echo "Difficulty statistics:\n";
ksort($difficultyCount);
foreach ($difficultyCount as $key => $value) {
    echo "Difficulty $key: $value\n";
}

echo "\n";

// Calculate the average reward for each difficulty
ksort($rewardsByDifficulty);
foreach ($rewardsByDifficulty as $difficulty => $rewards) {
    $averageReward = array_sum($rewards) / count($rewards);
    echo "Average reward for difficulty $difficulty: $averageReward ORE\n";
}

echo "\n";

// Calculate the sum of all rewards for each difficulty
$sumRewards = array();
foreach ($rewardsByDifficulty as $difficulty => $rewards) {
    $sumRewards = array_sum($rewards);

    echo "Sum of rewards for difficulty $difficulty: $sumRewards ORE\n";
}

echo "\n";
echo "Total number of tried hashes: $totalNumberOfHashes\n";
echo "Total count of no reward hashes: $noRewardCount\n";
echo "Total rewards: $totalRewards ORE\n";

?>
