<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\ExpenseResource as ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $expenses = $user->expenses;

        return $this->sendResponse(ExpenseResource::collection($expenses), 'Despesas retornadas com sucesso!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required|numeric|exists:App\Models\User,id',
            'date' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
            'description' => 'required|string|max:191',
            'value' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de validação.', $validator->errors());
        }

        $expense = Expense::create($input);

        return $this->sendResponse(new ExpenseResource($expense), 'Despesa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        $expense = Expense::findOrFail($expense->id);

        return $this->sendResponse(new ExpenseResource($expense), 'Despesa retornada com sucesso');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);
        $expense = Expense::findOrFail($expense->id);

        $input = $request->all();

        $validator = Validator::make($input, [
            'date' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
            'description' => 'required|string|max:191',
            'value' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de validação.', $validator->errors());
        }

        $expense->date = $input['date'];
        $expense->description = $input['description'];
        $expense->value = $input['value'];

        $expense->save();

        return $this->sendResponse(new ExpenseResource($expense), 'Despesa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense = Expense::findOrFail($expense->id);

        $expense->delete();

        return $this->sendResponse([], 'Despesa excluida com sucesso!');
    }
}
