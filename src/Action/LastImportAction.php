<?php

namespace App\Action;

use App\Model\LastImportModel;

class LastImportAction
{
    public function execute()
    {
        $lastImportModel = new LastImportModel();
        $lastImport = $lastImportModel->getLast();
        if(!$lastImport)
        {
            fwrite(STDERR, 'Not imported yet' . PHP_EOL);
        }
        else
        {
            fwrite(STDERR, sprintf('[%s] %s of %s - %s%% invalid: %s ' . PHP_EOL,
                $lastImport['date'],
                $lastImport['done'],
                $lastImport['total'],
                floor(($lastImport['done'] / $lastImport['total']) * 100),
                $lastImport['total'] - $lastImport['done']
            ));
        }
    }
}