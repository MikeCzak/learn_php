<?php

declare(strict_types = 1);

// Your Code
function getTransactionFiles():array {
    $files = array_slice(scandir(FILES_PATH), 2);
    $files = array_map(fn($file) => FILES_PATH . $file, $files);
    $files = array_values(array_filter($files, fn($file) => is_file($file)));
    return $files;
}

function readTransactions():array {
    $transactions = [];
    $files = getTransactionFiles();
    foreach ($files as $file) {
        $file = fopen($file, 'r');
        $headers = fgets($file);
        while (($line = fgetcsv($file, null,',')) !== false) {
            $transactions[] = $line;
        }
    }
    return $transactions;
}

function getCleanTransactions($data):array {
    foreach ($data as &$transaction) {
        $transaction[0] = date('M j, Y',strtotime($transaction[0]));
        $transaction[3] = str_replace('$', '', $transaction[3]);
        $transaction[3] = (float) str_replace(',', '', $transaction[3]);
    }
    return $data;
}