<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'budget_no'     => ['required', 'unique:purchase_details,budget_no,' . $this->pr_id],
            'pr_no'         => $this->checkIfPrNoIsRequired() && $this->requiredByRole('procurement') ? 'required' : '',
            'po_no'         => $this->checkIfPoNoIsRequired() && $this->requiredByRole('procurement') ? 'required' : '',
            'line_item'     => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'fund_source'   => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'program'       => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'date'          => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'creditor'      => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'saa_title'     => '',
            'obr_no'        => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'accounts'      => '',
            'obr_amount'    => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'obr_month'     => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'obr_year'      => $this->checkIfOBRDetailsIsRequired() && $this->requiredByRole('budget') ? 'required' : '',
            'iar'           => $this->checkIfIarIsRequired() && $this->requiredByRole('supply') ? 'required' : '',
            'dv_no'         => $this->checkIfDvIsRequired() && $this->requiredByRole('accounting') ? 'required' : '',
            'dv_amount'     => $this->checkIfDvIsRequired() && $this->requiredByRole('accounting') ? 'required' : '',
            'dv_month'      => $this->checkIfDvIsRequired() && $this->requiredByRole('accounting') ? 'required' : '',
            'dv_year'       => $this->checkIfDvIsRequired() && $this->requiredByRole('accounting') ? 'required' : '',
            'obr_unpaid'    => '',
            'ada_no'        => $this->checkIfAdaIsRequired() && $this->requiredByRole('cashier') ? 'required' : '',
            'remarks'       => '',
        ];
    }

    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'act_title'             => '',
            'saa_title'             => '',
            'budget_no.required'    => "Budget number is required.",
            'pr_no.required'        => "PR number is required.",
            'po_no.required'        => "PO number is required.",
            'line_item.required'    => "Line item is required.",
            'fund_source.required'  => "Fund source is required.",
            'program.required'      => "Program is required.",
            'date.required'         => "Date is required.",
            'creditor.required'     => "Creditor is required.",
            'obr_no.required'       => "OBR number is required.",
            'obr_amount.required'   => "Obligation amount is required.",
            'obr_month.required'    => "Month obligated is required.",
            'obr_year.required'     => "Year obligated is required.",
            'iar.required'          => "Inspection and Acceptance is required.",
            'dv_no.required'        => "DV number is required.",
            'dv_amount.required'    => "Amount disbursed is required.",
            'dv_month.required'     => "Month disbursed is required.",
            'dv_year.required'      => "Year disbursed is required.",
            'ada_no.required'       => "ADA/Check number is required.",
        ];
    }

    /**
     * Function: Check if the pr number is required
     */
    public function checkIfPrNoIsRequired(): bool
    {
        $budgetNoExist = \App\Models\PurchaseDetail::where('budget_no', $this->input('budget_no'))->first();
        return !empty($budgetNoExist) && $budgetNoExist->budget_no != null;
    }

    /**
     * Function: Check if the po no is empty
     */
    public function checkIfPoNoIsRequired(): bool
    {
        $budgetNoExist = \App\Models\PurchaseDetail::where('budget_no', $this->input('budget_no'))->first();
        return !empty($budgetNoExist) && $budgetNoExist->pr_no != null;
    }

    /**
     * Function: Check if the po no is empty
     */
    public function checkIfOBRDetailsIsRequired(): bool
    {
        $budgetNoExist = \App\Models\PurchaseDetail::where('budget_no', $this->input('budget_no'))->first();
        return !empty($budgetNoExist) && $budgetNoExist->po_no != null;
    }

    /**
     * Function: Check if the OBR number is empty
     */
    public function checkIfIarIsRequired(): bool
    {
        $budgetNoExist = \App\Models\PurchaseDetail::where('budget_no', $this->input('budget_no'))->first();
        $transactionExist = \App\Models\Transaction::where('id', $budgetNoExist ? $budgetNoExist->transaction_id : '0')->first();
        return !empty($budgetNoExist) && !empty($transactionExist) && $transactionExist->obr_no != null;
    }

    /**
     * Function: Check if the iar is true
     */
    public function checkIfDvIsRequired(): bool
    {
        $budgetNoExist = \App\Models\PurchaseDetail::where('budget_no', $this->input('budget_no'))->first();
        return !empty($budgetNoExist) && $budgetNoExist->iar;
    }

    /**
     * Function: Check if the DV number is empty
     */
    public function checkIfAdaIsRequired(): bool
    {
        $budgetNoExist = \App\Models\PurchaseDetail::where('budget_no', $this->input('budget_no'))->first();
        $transactionExist = \App\Models\Transaction::where('id', $budgetNoExist ? $budgetNoExist->transaction_id : '0')->first();
        return !empty($budgetNoExist) && !empty($transactionExist) && $transactionExist->dv_no != null;
    }

    // Auth::user()->roles[0]->name
    /**
     * Function: Check if the role makes the field required
     */
    public function requiredByRole($role): bool
    {
        if (Auth::user()->roles[0]->name == $role) {
            return true;
        } else {
            return false;
        }
    }
}
