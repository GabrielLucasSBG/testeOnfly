<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;
use Faker\Factory as Faker;

class ExpenseTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_can_create_new_expense()
    {
        $user = User::factory()->create();

        $faker = Faker::create();

        $data = [
            'user_id' => $user->id,
            'description' => $faker->sentence(5),
            'value' => $faker->randomFloat(2, 1, 1000),
            'date' => $faker->date('Y-m-d')
        ];

        $expense = Expense::create($data);

        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertEquals($data['user_id'], $expense->user_id);
        $this->assertEquals($data['description'], $expense->description);
        $this->assertEquals($data['value'], $expense->value);
        $this->assertEquals($data['date'], $expense->date);

        return $expense->id;
    }

    /**
     * @depends test_can_create_new_expense
     */

    public function test_can_update_existing_expense($expenseId): void
    {
        $expense = Expense::find($expenseId);

        $data = [
            'description' => 'Descricao atualizada',
            'value' => 87.32,
            'date' => '2023-02-25'
        ];

        $expense->update($data);

        $this->assertEquals($data['description'], $expense->description);
        $this->assertEquals($data['value'], $expense->value);
        $this->assertEquals($data['date'], $expense->date);
    }

    /**
     * @depends test_can_create_new_expense
     */

    public function test_can_delete_existing_expense($expenseId): void
    {
        $expense = Expense::find($expenseId);

        $expense->delete();

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}
