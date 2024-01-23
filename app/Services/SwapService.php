<?php

namespace App\Services;

use App\Models\CardNumber;
use App\Models\Transaction;
use App\Notifications\SwapNotification;
use App\Packages\Response\ResponseFormatter;
use Exception;
use Illuminate\Support\Str;

class SwapService
{
    /**
     * @throws Exception
     */
    public function swap(array $params): array
    {
        [$source, $destination] = [
            CardNumber::where('card_number', $params['source'])->with('bankAccount.user')->first(),
            CardNumber::where('card_number', $params['destination'])->with('bankAccount.user')->first(),
        ];

        if ($source->bankAccount->getAttribute('balance') < $params['balance'] + 500) {
            throw new Exception('balance is not enough');
        }

        \DB::transaction(static function () use ($params, $source, $destination) {
            $transaction = Transaction::create([
                'amount' => $params['balance'],
                'fee' => 500,
                'source_id' => $source->getAttribute('id'),
                'destination_id' => $destination->getAttribute('id'),
                'uuid' => Str::uuid()
            ]);
            $source->bankAccount->update([
                'amount' => $source->bankAccount->getAttribute('balance') - $transaction->getAttribute('amount') + 500,
            ]);

            $destination->bankAccount->update([
                'amount' => $destination->bankAccount->getAttribute('balance') + $transaction->getAttribute('amount'),
            ]);

            $source->bankAccount->user->notify(new SwapNotification(__('message.swap_source')));
            $destination->bankAccount->user->notify(new SwapNotification(__('message.swap_destination')));
        });


        return ResponseFormatter::success(array());
    }
}
