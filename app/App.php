<?php

declare(strict_types=1);

// Your Code

$files = scandir(FILES_PATH);

// iterate over every file in the transaction_files
function read_all_csv(): array
{
    $all_data = [];

    $files = scandir(FILES_PATH);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $fullPath = FILES_PATH . $file;

        if (is_file($fullPath)) {
            $handle = fopen($fullPath, 'r');

            if (!$handle) {
                continue; // Could not open file
            }

            $headers = fgetcsv($handle, 0, ",", '"', '\\');
            $rows = [];

            while (($data = fgetcsv($handle, 0, ",", '"', '\\')) !== false) {
                // combine the arrays to get a key value pair for each entry 
                $rows[] = array_combine($headers, $data);
            }

            fclose($handle);

            $all_data[$file] = [
                'headers' => $headers,
                'rows' => $rows,
            ];
        }
    }
    // echo "<pre>";
    // print_r($all_data);
    // echo "</pre>";
    return $all_data;
}


$all_data = read_all_csv();


function calculate_income_expenses_net($all_data): array{
    $total_income = 0;
    $total_expenses = 0;
    $net_total = 0;

    foreach ($all_data as $file_entry){
        foreach($file_entry['rows'] as $transaction){
            // filter out $ and , 
            $cleaned_amount =  (float) str_replace(['$', ','], '', $transaction['Amount']);
            if ($cleaned_amount > 0){
                $total_income += $cleaned_amount;
                $net_total += $cleaned_amount;
            }else{
                $total_expenses += $cleaned_amount;
                $net_total += $cleaned_amount;
            }

        }
    }
    $summary = ["total_income" => $total_income, "total_expenses" => $total_expenses, "net_total" => $net_total];
    // echo "<pre>";
    // print_r($summary);
    // echo "</pre>";
    return $summary;
}

$summary = calculate_income_expenses_net($all_data);