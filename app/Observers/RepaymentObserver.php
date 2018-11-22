<?php

namespace App\Observers;

use App\Models\Repayment;

class RepaymentObserver
{
    /**
     * Handle the repayment "created" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function created(Repayment $repayment)
    {
        $amount = $repayment->loan->paid_amount;
        $repayment->loan->paid_amount= $amount + $repayment->amount;

        $repayment->loan->balance_amount= $repayment->loan->amount - $repayment->loan->paid_amount;
    }

    /**
     * Handle the repayment "updated" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function updated(Repayment $repayment)
    {
        $amount = $repayment->loan->paid_amount;
        $original = (object)$repayment->getOriginal();
        $repayment->loan->paid_amount= ($amount - $original->amount) + $repayment->amount;

        $repayment->loan->balance_amount= $repayment->loan->amount - $repayment->loan->paid_amount;
    }

    /**
     * Handle the repayment "deleted" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function deleted(Repayment $repayment)
    {
        $amount = $repayment->loan->paid_amount;
        $original = (object)$repayment->getOriginal();
        $repayment->loan->paid_amount= ($amount - $original->amount);
        $repayment->loan->balance_amount= $repayment->loan->amount - $repayment->loan->paid_amount;
    }

    /**
     * Handle the repayment "restored" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function restored(Repayment $repayment)
    {
        $amount = $repayment->loan->paid_amount;
        $repayment->loan->paid_amount= $amount + $repayment->amount;
        $repayment->loan->balance_amount= $repayment->loan->amount - $repayment->loan->paid_amount;
    }

    /**
     * Handle the repayment "force deleted" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function forceDeleted(Repayment $repayment)
    {
        $amount = $repayment->loan->paid_amount;
        $original = (object)$repayment->getOriginal();
        $repayment->loan->paid_amount= ($amount - $original->amount);
        $repayment->loan->balance_amount= $repayment->loan->amount - $repayment->loan->paid_amount;
    }
}
