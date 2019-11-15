<?php

namespace App\Action;

use App\AppException;
use App\Database\DatabaseFactory;
use App\File\CsvFile;
use App\Model\LastImportModel;
use App\Model\ProductModel;

class ImportAction
{
    const INVALID_ITEMS_FILE = APP_DIR . '/data/invalid-items.csv';

    /** @var CsvFile $csvFile */
    protected $csvFile;

    public function __construct(CsvFile $csvFile)
    {
        $this->csvFile = $csvFile;
    }

    /**
     * @throws AppException
     */
    public function validate(): void
    {
        if(!$this->csvFile->isReadable())
        {
            throw new AppException(sprintf('file \'%s\' is not readable', $this->csvFile->getFilename()));
        }
    }

    /**
     * @throws AppException
     */
    public function execute(): void
    {
        $this->truncateProducts();
        $import = new LastImportModel();
        $this->csvFile->read();
        $totalLines = $this->csvFile->getTotal();
        $invalidItemsFile = $this->prepareInvalidItemFile();
        $invalidItemsFile->setColumns(array_keys($this->csvFile->getColumns()));
        $invalidItems = [];
        $import->setTotal($totalLines);
        $import->setDone(0);
        $import->setDate(date('Y-m-d H:i:s'));
        $import->save();

        foreach($this->csvFile->getData() as $index => $item)
        {
            $this->printProgress($index + 1, $totalLines);

            $product = new ProductModel();
            $product->setCode($item[$this->csvFile->getColumnIndex('mpn')]);
            $product->setQuantity($item[$this->csvFile->getColumnIndex('qty')]);
            $description = $item[$this->csvFile->getColumnIndex('description')];
            $description = substr($description, strlen($description) - 4, 4);
            $product->setDescription($description);
            $product->setPrice($item[$this->csvFile->getColumnIndex('price')]);

            if(!$product->save())
            {
                $invalidItems[] = $item;
            }
        }
        $this->saveInvalidItems($invalidItemsFile, $invalidItems);
        $import->setDone($totalLines - sizeof($invalidItems));
        $import->update();
    }


    protected function printProgress($done, $total)
    {
        $percent = floor(($done / $total) * 100);
        fwrite(STDERR, sprintf("\033[0G\033[2K [%s/%s] progress: %s%% ", $done, $total, $percent));
        if($percent == 100)
        {
            fwrite(STDERR, sprintf(" - done %s", PHP_EOL));
        }
    }

    /**
     * @param CsvFile $invalidItemsFile
     * @param array $items
     * @throws AppException
     */
    private function saveInvalidItems(CsvFile $invalidItemsFile, array $items): void
    {
        if(!$invalidItemsFile->isWriteable())
        {
            throw new AppException(sprintf('File \'%s\' is not writable', $invalidItemsFile->getFilename()));
        }

        $invalidItemsFile->save($items, ';');
    }

    private function prepareInvalidItemFile(): CsvFile
    {
        if(!file_exists(self::INVALID_ITEMS_FILE))
        {
            touch(self::INVALID_ITEMS_FILE);
        }
        $invalidItemsFile = new CsvFile(self::INVALID_ITEMS_FILE);

        return $invalidItemsFile;
    }

    /**
     * @throws AppException
     */
    private function truncateProducts()
    {
        $database = DatabaseFactory::getInstance();
        $database->truncate('products');
    }
}