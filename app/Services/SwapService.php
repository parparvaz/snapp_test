<?php

namespace App\Services;

use App\Models\CardNumber;
use App\Models\Transaction;
use App\Notifications\SwapNotification;
use App\Packages\Response\ResponseFormatter;
use DB;
use Exception;
use Illuminate\Support\Str;
use function Laravel\Prompts\error;

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
        if ($source->bankAccount->getAttribute('balance') < ($params['balance'] + Transaction::FEE)) {
            return ResponseFormatter::entity(error:[
                'balance' => __('validation.lte.numeric', ['attribute' => 'balance', 'value' => $source->bankAccount->getAttribute('balance') - Transaction::FEE])
            ]);
        }

        DB::transaction(static function () use ($params, $source, $destination) {
            $transaction = Transaction::create([
                'amount' => $params['balance'],
                'fee' => Transaction::FEE,
                'source_id' => $source->getAttribute('id'),
                'destination_id' => $destination->getAttribute('id'),
                'uuid' => Str::uuid()
            ]);

            $source->bankAccount->update([
                'balance' => $source->bankAccount->getAttribute('balance') - ($transaction->getAttribute('amount') + Transaction::FEE),
            ]);

            $destination->bankAccount->update([
                'balance' => $destination->bankAccount->getAttribute('balance') + $transaction->getAttribute('amount'),
            ]);

            $source->bankAccount->user->notify(new SwapNotification(__('message.swap.sms.source', ['balance' => $params['balance'], 'fee' => Transaction::FEE])));
            $destination->bankAccount->user->notify(new SwapNotification(__('message.swap.sms.destination', ['balance' => $params['balance']])));
        });


        return ResponseFormatter::success(data: array(), message: __('message.swap.success'));
    }
}
