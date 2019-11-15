<?php

namespace App\Action;

use App\Model\LastImportModel;

class LastImportAction extends Action
{
    public function execute()
    {
        $lastImportModel = new LastImportModel();
        $lastImport = $lastImportModel->getLast();
        if(!$lastImport)
        {
            $this->render('lastImportEmpty.phtml',[]);
        }
        else
        {
            $this->render('lastImport.phtml', [
                'date' => $lastImport['date'],
                'done' => $lastImport['done'],
                'total' => $lastImport['total'],
                'percent' => floor(($lastImport['done'] / $lastImport['total']) * 100),
            ]);
        }
    }
}