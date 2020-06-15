<?php

namespace Wingly\Pwinty;

trait Orderer
{
    public function newOrder()
    {
        return new OrderBuilder($this);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, $this->getForeignKey())->orderBy('created_at', 'desc');
    }
}
