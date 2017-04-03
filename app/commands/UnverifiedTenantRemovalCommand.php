<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use IntegrityInvoice\Services\Tenant\Remover as TenantRemover;
use Carbon\Carbon;

class UnverifiedTenantRemovalCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'integrity:remove-old-unverified-tenants';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clean up database, remove old unverified accounts';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // Remove tenants older than one month
        $oneMonth = Carbon::now()->subMonth();
        $oldTenants = Tenant::where('created_at', '<=', $oneMonth)->where('verified', '=', 0)->get();

        foreach($oldTenants as $oldTenant){
            $removerService = App::make('IntegrityInvoice\Services\Tenant\Remover');
			$removerService->commandCancel($oldTenant->tenantID);
        }
        return true;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
