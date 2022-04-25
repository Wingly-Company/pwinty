<?php

namespace Wingly\Pwinty;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_COMPLETE = 'Complete';
    public const STATUS_CANCELLED = 'Cancelled';

    protected $guarded = [];

    public function syncStatus()
    {
        $pwintyOrder = $this->asPwintyOrder();

        $this->pwinty_status = $pwintyOrder->status->stage;

        $this->save();
    }

    public function cancel()
    {
        app(Pwinty::class)->cancelOrder($this->pwinty_id);

        $this->syncStatus();

        return $this;
    }

    public function cancelled()
    {
        return $this->pwinty_status === self::STATUS_CANCELLED;
    }

    public function submitted()
    {
        return $this->pwinty_status === self::STATUS_SUBMITTED;
    }

    public function asPwintyOrder()
    {
        return app(Pwinty::class)->getOrder($this->pwinty_id);
    }

    public function owner()
    {
        $model = config('pwinty.model');

        return $this->belongsTo($model, (new $model())->getForeignKey());
    }
}
