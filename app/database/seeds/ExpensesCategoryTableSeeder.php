<?php
 
class ExpensesCategoryTableSeeder extends Seeder {
	
	public $expenseItems = array(
		array('category' => 'Uncategorised', 'expense_name' => 'Uncategorised'),
		array('category' => 'Marketing Expenses', 'expense_name' => 'Advertising Costs'),
		array('category' => 'Marketing Expenses', 'expense_name' => 'Representation Costs'),
		array('category' => 'Marketing Expenses', 'expense_name' => 'Food and Meals'),
		array('category' => 'Marketing Expenses', 'expense_name' => 'Travel (Employees)'),
		array('category' => 'Marketing Expenses', 'expense_name' => 'Travel (Owner)'),		
		
		array('category' => 'Office Costs', 'expense_name' => 'Office Supplies'),
		array('category' => 'Office Costs', 'expense_name' => 'Postage'),
		array('category' => 'Office Costs', 'expense_name' => 'Phone & Communication'),
		array('category' => 'Office Costs', 'expense_name' => 'Internet'),
		array('category' => 'Office Costs', 'expense_name' => 'Office Equipment'),
		array('category' => 'Office Costs', 'expense_name' => 'Books and Magazines'),
		array('category' => 'Office Costs', 'expense_name' => 'Training and Education'),
		array('category' => 'Office Costs', 'expense_name' => 'Small Equipment'),
		array('category' => 'Office Costs', 'expense_name' => 'Software'),
		array('category' => 'Office Costs', 'expense_name' => 'Hardware'),
		array('category' => 'Office Costs', 'expense_name' => 'Other Office Equipment'),		
		
		array('category' => 'Sales Costs', 'expense_name' => 'Gifts and Giveaways'),
		array('category' => 'Sales Costs', 'expense_name' => 'Sales Provisions and Commissions'),
		array('category' => 'Sales Costs', 'expense_name' => 'Shipping, Transport and Logistics'),
		array('category' => 'Sales Costs', 'expense_name' => 'Packaging'),
		array('category' => 'Sales Costs', 'expense_name' => 'Transport Insurance'),
		
		array('category' => 'Business Services', 'expense_name' => 'Consulting Services'),
		array('category' => 'Business Services', 'expense_name' => 'Research Services'),
		array('category' => 'Business Services', 'expense_name' => 'Creative Services'),
		array('category' => 'Business Services', 'expense_name' => 'Other Business Services'),
		
		array('category' => 'Facilities and Buildings', 'expense_name' => 'Rent'),
		array('category' => 'Facilities and Buildings', 'expense_name' => 'Heating and Ventilation'),
		array('category' => 'Facilities and Buildings', 'expense_name' => 'Gas'),
		array('category' => 'Facilities and Buildings', 'expense_name' => 'Electricity'),
		array('category' => 'Facilities and Buildings', 'expense_name' => 'Water'),
		array('category' => 'Facilities and Buildings', 'expense_name' => 'Trash'),
		array('category' => 'Facilities and Buildings', 'expense_name' => 'Cleaning'),
		array('category' => 'Facilities and Buildings', 'expense_name' => 'Maintenance'),		
		
		array('category' => 'Cars and other Vehicles', 'expense_name' => 'Car Running Costs'),
		array('category' => 'Cars and other Vehicles', 'expense_name' => 'Car Insurance'),
		array('category' => 'Cars and other Vehicles', 'expense_name' => 'Car Rental'),
		array('category' => 'Cars and other Vehicles', 'expense_name' => 'Car Taxes'),
		array('category' => 'Cars and other Vehicles', 'expense_name' => 'Car Purchase'),
		array('category' => 'Cars and other Vehicles', 'expense_name' => 'Other Vehicle Purchase'),		
		
		array('category' => 'Legal and Accounting Fees', 'expense_name' => 'Legal Fees'),
		array('category' => 'Legal and Accounting Fees', 'expense_name' => 'Annual Report and Tax Fees'),
		array('category' => 'Legal and Accounting Fees', 'expense_name' => 'Bookkeeping Costs'),
		
		array('category' => 'Insurance and Fees', 'expense_name' => 'Insurance Premiums'),
		array('category' => 'Insurance and Fees', 'expense_name' => 'Membership Fees'),
		array('category' => 'Insurance and Fees', 'expense_name' => 'License Fees'),
		
		array('category' => 'Inventory Purchase', 'expense_name' => 'Finished Goods'),
		array('category' => 'Inventory Purchase', 'expense_name' => 'Raw Materials'),
		array('category' => 'Inventory Purchase', 'expense_name' => 'Subcontracting'),		
		
		array('category' => 'Bank and Financing Costs', 'expense_name' => 'Financial Fees'),
		array('category' => 'Bank and Financing Costs', 'expense_name' => 'Interest Paid'),
		array('category' => 'Bank and Financing Costs', 'expense_name' => 'Interest Received'),
		array('category' => 'Bank and Financing Costs', 'expense_name' => 'Owner Payment'),
		array('category' => 'Bank and Financing Costs', 'expense_name' => 'Owner Withdrawal'),		
		
		array('category' => 'Wage and Salaries', 'expense_name' => 'Employee Salary'),
		array('category' => 'Wage and Salaries', 'expense_name' => 'Employee Salary Taxes'),
		array('category' => 'Wage and Salaries', 'expense_name' => 'Temporary Employee Salary'),
		array('category' => 'Wage and Salaries', 'expense_name' => 'Temporary Employee Salary Taxes'),
		array('category' => 'Wage and Salaries', 'expense_name' => 'Social Security'),
		array('category' => 'Wage and Salaries', 'expense_name' => 'Medical Expenses and Sick Leave'),
		array('category' => 'Wage and Salaries', 'expense_name' => 'Unemployment Costs'),
		array('category' => 'Wage and Salaries', 'expense_name' => 'Non Monetary Salary'),
		array('category' => 'Wage and Salaries', 'expense_name' => 'Transport Costs from/to Work'),		
		
		array('category' => 'Taxes and VAT', 'expense_name' => 'Income tax '),
		array('category' => 'Taxes and VAT', 'expense_name' => 'Company tax '),
		array('category' => 'Taxes and VAT', 'expense_name' => 'VAT Payment'),
		array('category' => 'Taxes and VAT', 'expense_name' => 'VAT Refund'),
  	 	
	);
	
	
	public function run()
	{ 
		ExpenseCategory::truncate();

		foreach(range(0, sizeof($this->expenseItems) - 1) as $index)
		{
			ExpenseCategory::create(array(
				'category' => $this->expenseItems[$index]['category'],
				'expense_name' => $this->expenseItems[$index]['expense_name']
			));
		}
	}

}

