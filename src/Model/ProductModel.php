<?php

namespace App\Model;

class ProductModel extends Model
{
    protected $data = [
        'mpn' => null,
        'qty' => null,
        'description' => null,
        'price' => null,
    ];

    /**
     * @param string $value
     * @return $this
     */
    public function setCode(string $value): self
    {
        $this->data['mpn'] = $value;

        return $this;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->data['qty'] = $quantity;

        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    /**
     * @param int $price
     * @return $this
     */
    public function setPrice(int $price): self
    {
        $this->data['price'] = $price;

        return $this;
    }

    /**
     * @return bool
     */
    protected function isValid(): bool
    {
        if(!$this->data['price'] > 0)
        {
            return false;
        }
        if(!$this->data['qty'] > 0)
        {
            return false;
        }
        if(!strlen($this->data['mpn']))
        {
            return false;
        }
        if(!preg_match('/^[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}$/', $this->data['description']))
        {
            return false;
        }

        return true;
    }

    protected function getTableName(): string
    {
        return 'product';
    }

    protected function getPrimaryKey(): string
    {
        return 'id';
    }
}