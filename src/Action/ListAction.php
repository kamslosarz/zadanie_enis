<?php

namespace App\Action;

use App\AppException;
use App\Database\DatabaseFactory;

class ListAction extends Action
{
    /**
     * @param string|null $code
     * @throws AppException
     */
    public function execute(string $code = null)
    {
        if(!$code)
        {
            return $this->multipleResults();
        }
        else
        {
            return $this->singleResult($code);
        }
    }

    /**
     * @param string|null $code
     * @throws AppException
     */
    protected function singleResult(string $code = null)
    {
        $database = DatabaseFactory::getInstance();
        $items = $database->select('product', 'WHERE mpn=?', 'ORDER BY `id` DESC', 'LIMIT 0,1', [$code]);

        $this->render('list.phtml', [
            'items' => $items
        ]);
    }

    private function multipleResults()
    {
        $database = DatabaseFactory::getInstance();
        $items = $database->select('product', '', 'ORDER BY `id` DESC');
        $this->render('list.phtml', [
            'items' => $items,
            'prevPage' => $prevPage,
            'nestPage' => $nestPage,
        ]);
    }
}