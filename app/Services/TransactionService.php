<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\Transaction;
use App\Models\User;
use App\Packages\Response\ResponseFormatter;

class TransactionService
{

    public function index(): array
    {
        $users = User::selectRaw('users.*, count(*) AS count')
            ->join('bank_accounts', 'users.id', '=', 'bank_accounts.user_id')
            ->join('card_numbers', 'bank_accounts.id', '=', 'card_numbers.bank_account_id')
            ->join('transactions', 'card_numbers.id', '=', 'transactions.source_id')
            ->where('transactions.created_at', '>=', now()->subMinutes(10))
            ->orderByDesc('count')
            ->groupByRaw('users.id')
            ->take(3)
            ->get();

        foreach ($users as $index => $user) {
            $users[$index]['transactions'] = Transaction::Join('card_numbers', 'transactions.source_id','=','card_numbers.id')
                ->join('bank_accounts', 'card_numbers.bank_account_id','=','bank_accounts.id')
                ->where('bank_accounts.user_id', $user->getAttribute('id'))
                ->orderByDesc('transactions.created_at')
                ->take(10)
                ->get();
        }

        return ResponseFormatter::success(data: UserResource::collection($users));
    }
}
