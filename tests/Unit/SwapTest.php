<?php

namespace Tests\Unit;

use App\Models\BankAccount;
use App\Models\CardNumber;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SwapTest extends TestCase
{
    use RefreshDatabase;

    public function testCardNumberValidation(): void
    {
        $this->seed();

        $this->postJson(
            uri: route('swap'),
        )->assertJson([
            'status' => 'error',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'data' => [
                'source' => __('validation.required', ['attribute' => 'source']),
                'destination' => __('validation.required', ['attribute' => 'destination']),
                'balance' => __('validation.required', ['attribute' => 'balance'])
            ]
        ]);

        $this->postJson(
            uri: route('swap'),
            data: [
                'source' => '1111111111111111',
                'destination' => '1111111111111111',
                'balance' => Transaction::MINIMUM_SWAP - 1,
            ],
        )->assertJson([
            'status' => 'error',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'data' => [
                'source' => __('validation.exists', ['attribute' => 'source']) . ', ' . __('validation.different', ['attribute' => 'source', 'other' => 'destination']) . ', ' . __('validation.ir_bank_card_number', ['attribute' => 'source']),
                'destination' => __('validation.exists', ['attribute' => 'destination']) . ', ' . __('validation.different', ['attribute' => 'destination', 'other' => 'source']) . ', ' . __('validation.ir_bank_card_number', ['attribute' => 'destination']),
                'balance' => __('validation.gte.numeric', ['attribute' => 'balance', 'value' => Transaction::MINIMUM_SWAP])
            ]
        ]);

        $this->postJson(
            uri: route('swap'),
            data: [
                'balance' => Transaction::MAXIMUM_SWAP + 1,
            ],
        )->assertJson([
            'status' => 'error',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'data' => [
                'balance' => __('validation.lte.numeric', ['attribute' => 'balance', 'value' => Transaction::MAXIMUM_SWAP])
            ]
        ]);

        $balance = random_int(Transaction::MINIMUM_SWAP, Transaction::MAXIMUM_SWAP);
        $sourceBankAccount = BankAccount::factory()->balance($balance + Transaction::MINIMUM_SWAP)->create();
        $source = CardNumber::factory()->bankAccount($sourceBankAccount)->cardNumber('6037991785973854')->create();
        $destinationBankAccount = BankAccount::factory()->balance(0)->create();
        $destination = CardNumber::factory()->bankAccount($destinationBankAccount)->cardNumber('6362141806255072')->create();

        $this->postJson(
            uri: route('swap'),
            data: [
                'source' => $source->getAttribute('card_number'),
                'destination' => $destination->getAttribute('card_number'),
                'balance' => $balance + Transaction::MINIMUM_SWAP,
            ],
        )->assertJson([
            'status' => 'error',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'data' => [
                'balance' => __('validation.lte.numeric', ['attribute' => 'balance', 'value' => $balance + Transaction::MINIMUM_SWAP - Transaction::FEE]),
            ]
        ]);

        $balance = random_int(Transaction::MINIMUM_SWAP, Transaction::MAXIMUM_SWAP);
        $sourceBankAccount = BankAccount::factory()->balance($balance + Transaction::MINIMUM_SWAP)->create();
        $source = CardNumber::factory()->bankAccount($sourceBankAccount)->cardNumber('6063731144301778')->create();

        $this->postJson(
            uri: route('swap'),
            data: [
                'source' => $source->getAttribute('card_number'),
                'destination' => $destination->getAttribute('card_number'),
                'balance' => $balance,
            ],
        )->assertJson([
            'status' => 'success',
            'code' => Response::HTTP_OK,
        ]);

        $this->assertDatabaseHas('transactions', [
            'fee' => Transaction::FEE,
            'amount' => $balance,
            'source_id' => $source->getAttribute('id'),
            'destination_id' => $destination->getAttribute('id'),
            'status' => Transaction::PENDING
        ]);

        $this->assertDatabaseHas('bank_accounts', [
            'id' => $sourceBankAccount->getAttribute('id'),
            'balance' => Transaction::FEE,
        ]);

        $this->assertDatabaseHas('bank_accounts', [
            'id' => $destinationBankAccount->getAttribute('id'),
            'balance' => $balance,
        ]);

        $balance = random_int(Transaction::MINIMUM_SWAP, Transaction::MAXIMUM_SWAP);
        $sourceBankAccount = BankAccount::factory()->balance($balance + Transaction::MINIMUM_SWAP)->create();
        $source = CardNumber::factory()->bankAccount($sourceBankAccount)->cardNumber('6221061230055487')->create();
        $destinationBankAccount = BankAccount::factory()->balance(0)->create();
        $destination = CardNumber::factory()->bankAccount($destinationBankAccount)->cardNumber('6274121198273112')->create();

        $this->postJson(
            uri: route('swap'),
            data: [
                'source' => '۶2210۶1230055487',
                'destination' => '٦274121198273112',
                'balance' => $balance,
            ],
        )->assertJson([
            'status' => 'success',
            'code' => Response::HTTP_OK,
        ]);

        $this->assertDatabaseHas('transactions', [
            'fee' => Transaction::FEE,
            'amount' => $balance,
            'source_id' => $source->getAttribute('id'),
            'destination_id' => $destination->getAttribute('id'),
            'status' => Transaction::PENDING
        ]);

        $this->assertDatabaseHas('bank_accounts', [
            'id' => $sourceBankAccount->getAttribute('id'),
            'balance' => Transaction::FEE,
        ]);

        $this->assertDatabaseHas('bank_accounts', [
            'id' => $destinationBankAccount->getAttribute('id'),
            'balance' => $balance,
        ]);
    }
}
