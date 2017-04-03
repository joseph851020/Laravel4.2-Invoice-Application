<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use IntegrityInvoice\Notifications\FeatureUpdates;

class FeatureUpdatesNotifierCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'integrity:notify-integrity-subscribers';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Notify all Integrity Subscribers by email';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

    private $featureUpdatesNotifier;

	public function __construct(FeatureUpdates $featureUpdatesNotifier)
	{
		parent::__construct();
        $this->featureUpdatesNotifier = $featureUpdatesNotifier;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$feature = $this->getFeatureUpdates();

        // $body = View::make('email.feature_notifier', $data)->render();

        $this->featureUpdatesNotifier->notify($feature['title'], $feature['body']);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('featureId', InputArgument::REQUIRED, 'Id of th feature to notify subscribers about'),
		);
	}


    private function getFeatureUpdates()
    {

        // return Feature::findOrfail($this->argument('featureId'));
        return [
            'title' => 'Feature title',
            'body' => 'The body of the feature'
        ];
    }

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */

    /*
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

    */



}
