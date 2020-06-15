<?php

namespace Wingly\Pwinty;

use Illuminate\Database\Eloquent\Model;
use Wingly\Pwinty\Exceptions\OrderUpdateFailure;

class Order extends Model
{
    const STATUS_NOT_YET_SUBMITTED = 'NotYetSubmitted';
    const STATUS_SUBMITTED = 'Submitted';
    const STATUS_AWAITING_PAYMENT = 'AwaitingPayment';
    const STATUS_COMPLETE = 'Complete';
    const STATUS_CANCELLED = 'Cancelled';

    protected $guarded = [];

    public function syncStatus()
    {
        $pwintyOrder = $this->asPwintyOrder();

        $this->pwinty_status = $pwintyOrder->status;

        $this->save();
    }

    public function addImage(
        string $sku,
        string $url,
        int $copies = 1,
        string $sizing = null
    ) {
        $this->guardAgainstSubmitted();

        app(Pwinty::class)->addImage($this->pwinty_id, array_filter([
            'sku' => $sku,
            'url' => $url,
            'copies' => $copies,
            'sizing' => $sizing,
        ]));

        return $this;
    }

    public function cancel()
    {
        $this->guardAgainstSubmitted();

        app(Pwinty::class)->updateStatus($this->pwinty_id, self::STATUS_CANCELLED);

        $this->syncStatus();

        return $this;
    }

    public function submit()
    {
        $this->guardAgainstInvalid();

        app(Pwinty::class)->updateStatus($this->pwinty_id, self::STATUS_SUBMITTED);

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

        return $this->belongsTo($model, (new $model)->getForeignKey());
    }

    protected function guardAgainstInvalid()
    {
        $submissionStatus = app(Pwinty::class)->checkSubmissionStatus($this->pwinty_id);

        if (! $submissionStatus->isValid) {
            throw OrderUpdateFailure::invalidOrder($this);
        }
    }

    protected function guardAgainstSubmitted()
    {
        if ($this->pwinty_status !== self::STATUS_NOT_YET_SUBMITTED) {
            throw OrderUpdateFailure::nonUpdatableStatus($this);
        }
    }
}
