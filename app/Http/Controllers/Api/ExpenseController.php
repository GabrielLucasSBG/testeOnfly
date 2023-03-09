<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\ExpenseResource as ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::all();

        return $this->sendResponse(ExpenseResource::collection($expenses), 'Despesas retornadas com sucesso!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required|numeric|exists:users',
            'date' => 'required|date|before_or_equal' . now()->format('Y-m-d'),
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
    public function show($id)
    {
        $expense = Expense::find($id);

        if (empty($expense)) {
            return $this->sendError('Despesa não encontrada');
        }

        return $this->sendResponse(new ExpenseResource($expense), 'Despesa retornada com sucesso');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'date' => 'required|date|before_or_equal' . now()->format('Y-m-d'),
            'description' => 'required|string|max:191',
            'value' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de validação.', $validator->errors());
        }

        $expense = Expense::find($id);

        $expense->date = $input['date'];
        $expense->description = $input['description'];
        $expense->value = $input['value'];

        $expense->save();

        return $this->sendResponse(new ExpenseResource($expense), 'Despesa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return $this->sendResponse([], 'Despesa excluida com sucesso!');
    }
}
