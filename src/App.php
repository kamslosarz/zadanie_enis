<?php

namespace App;

use App\Action\ImportAction;
use App\Action\LastImport;
use App\Action\LastImportAction;
use App\Action\ListAction;
use App\File\CsvFile;

class App
{
    /**
     * @param string $filename
     * @throws AppException
     */
    public function import(string $filename): void
    {
        $csvFile = new CsvFile($filename);
        $import = new ImportAction($csvFile);

        $import->validate();
        $import->execute();
    }

    public function lastImport(): void
    {
        $lastImport = new LastImportAction();
        $lastImport->execute();
    }

    public function list(string $code = null): void
    {
        $list = new ListAction();
        $list->execute($code);
    }
}