<?php

namespace Chenzi\LaravelMNS;

use Chenzi\LaravelMNS\Console\MNSWorkCommand;
use Illuminate\Support\ServiceProvider;
use Chenzi\LaravelMNS\Connectors\MNSConnector;
use Chenzi\LaravelMNS\Console\MNSFlushCommand;
use Illuminate\Contracts\Debug\ExceptionHandler;

class LaravelMNSServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	private $MNSQueueManager;

	public function boot() {
		$this->MNSQueueManager = new MNSQueueManager( $this->app );
		$this->registerConnector( $this->MNSQueueManager );
		$this->commands( 'command.queue.mns.flush' );
		$this->commands( 'command.queue.mns.work' );
	}

	/**
	 * Add the connector to the queue drivers.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerCommand();
	}

	/**
	 * Register the MNS queue connector.
	 *
	 * @param MNSQueueManager $manager
	 *
	 * @return void
	 */
	protected function registerConnector( $manager ) {
		$mns = config( 'queue.connections.mns' );
		if ( $mns ) {
			foreach ( $mns as $name => $config ) {
				$manager->addConnector( $name, function () {
					return new MNSConnector();
				} );
			}
		}
	}

	/**
	 * Register the MNS queue command
	 */
	private function registerCommand() {
		$this->app->singleton( 'command.queue.mns.flush', function () {
			return new MNSFlushCommand();
		} );
		$this->app->singleton( 'command.queue.mns.work', function () {
			return new MNSWorkCommand( new MNSWorker(
				$this->MNSQueueManager, $this->app['events'], $this->app[ ExceptionHandler::class ]
			) );
		} );
	}
}
