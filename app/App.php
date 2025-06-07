<?php

declare(strict_types = 1);

// Your Code

$files = scandir(FILES_PATH);

// iterate over every file in the transaction_files
foreach ($files as $file) {
    // exclude default dir
    if ($file !== '.' && $file !== '..') {
        // take the current path of the current file in the loop
        $fullPath = FILES_PATH . $file;

        if (is_file($fullPath)) {

            $csvFile = new SplFileObject($fullPath);
            $csvFile->setFlags(SplFileObject::READ_CSV);

            $headers = [];
            $rows = [];

            foreach ($csvFile as $index => $row) {
                if ($csvFile->eof() || $row === [null]) continue;

                if ($index === 0) {
                    $headers = $row;
                } else {
                    $rows[] = array_combine($headers, $row);
                }
            }
            echo "<pre>";
            
            print_r($rows);
            echo "</pre>";
        }
    }
}
