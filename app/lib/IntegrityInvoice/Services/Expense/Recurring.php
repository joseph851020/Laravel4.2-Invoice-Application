<?php namespace IntegrityInvoice\Services\Expense;

use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Repositories\ExpenseRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\Expense\Creator;
use IntegrityInvoice\Services\Expense\Updater;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Filesystem;

class Recurring {

    public $expense;
    public $preference;
    public $tenant;

    public function __construct(TenantRepositoryInterface $tenant, ExpenseRepositoryInterface $expense, PreferenceRepositoryInterface $preference)
    {
        $this->expense = $expense;
        $this->preference = $preference;
        $this->tenant = $tenant;
    }

    public function auto_copy($expense){

        $expense_to_copy = $expense;
        $tenantID = $expense_to_copy->tenantID;
        $user_id = $expense_to_copy->user_id;
        $merchant_id = $expense_to_copy->merchant_id;
        $amount = $expense_to_copy->amount;
        $category_id = $expense_to_copy->category_id;
        $note = $expense_to_copy->note;
        $category_id = $expense_to_copy->category_id;
        $currency_code = $expense_to_copy->currency_code;
        $ref_no = $expense_to_copy->ref_no;
        $file = $expense_to_copy->file;
        $archived = $expense_to_copy->archived;
        $tax1_val = $expense_to_copy->tax1_val;
        $tax2_val = $expense_to_copy->tax2_val;
        $user_id = $expense_to_copy->user_id;

        $creatorService = new Creator($this->expense, $this);
        $newExpense = $creatorService->auto_create(array(
            'amount' => $amount,
            'merchant_id' => $merchant_id,
            'ref_no' => $ref_no,
            'note' => $note,
            'file' => $file,
            'tax1_val' => $tax1_val,
            'currency_code' => $currency_code,
            'category_id' => $category_id,
            'expense_date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id' => $user_id,
            'tenantID' => $tenantID
        ));

    }// End auto_copy

    public function autoGenerateExpenseCron(){

        // Set time limit
        set_time_limit(0);

        // Get all recurring expenses
        $recurring_expenses = $this->expense->getExpensesRecurringToday();
        // Loop over each recurring expense
        foreach($recurring_expenses as $recurring_expense){
            $tenantID = $recurring_expense->tenantID;

            // Verify subscription is active and recurring is active
            if($this->tenant->isActive($tenantID) && $recurring_expense->recur_status == 1){
                // Copy the expense
                $this->auto_copy($recurring_expense);

                // Update the next recurring date
                $recur_schedule = $recurring_expense->recur_schedule;
                $expense_id = $recurring_expense->id;
                $recur_next_date = $this->getNextRecurringDate($recur_schedule);

                $updateService = new Updater($this->expense, $this);
                $updateService->updateAfterRecurring($tenantID, $expense_id, array(
                    'recur_next_date' => $recur_next_date,
                    'updated_at' => Carbon::now()
                ));
            }

        }

        return true;

    }// End Auto Cron

    public function getNextRecurringDate($recur_schedule){

        // $today = date('Y-m-d', strtotime('today'));
        $today = Carbon::now();

        switch($recur_schedule){

            case "Every week":
                $next_date = $today->addDays(7);
                break;

            case "Every two weeks":
                $next_date = $today->addDays(14);
                break;

            case "Every month":
                $next_date = $today->addMonth();
                break;

            case "Every two months":
                $next_date = $today->addMonths(2);
                break;

            case "Every three months":
                $next_date = $today->addMonths(3);
                break;

            case "Every four months":
                $next_date = $today->addMonths(4);
                break;
            case "Every six months":
                $next_date = $today->addMonths(6);
                break;

            case "Every twelve months":
                $next_date = $today->addYear();
                break;

            default:
                $next_date = $today->addMonth();
                break;
        }

        return $next_date;

    }//

}