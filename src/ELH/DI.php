<?php


	class ELH_DI extends tad_DI52_Container{

		/**
		 * @var tad_DI52_Container
		 */
		protected static $instance;

		/**
		 * @return ELH_DI
		 */
		public static function instance() {
			if ( empty( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * @return ELH_DI
		 */
		public function set_dependencies() {
			$this->set_ctor( 'keychain', 'ELH_Keychain' );
			$this->set_ctor( 'api', 'ELH_Api' );
			$this->set_ctor( 'sync_strategy_selector', 'ELH_SyncStrategySelector' );

			// Sync steps
			$dependencies = array( '@keychain', '@api' );
			$this->set_ctor( 'listings_retriever', 'ELH_ListingRetriever::instance', $dependencies );
			$this->set_ctor( 'shop_retriever', 'ELH_ShopRetriever::instance', $dependencies )
			     ->set_next( '@listings_retriever' );

			// Sync set up
			$dependencies = array( '@sync_strategy_selector' );
			$this->set_ctor( 'synchronizer', 'ELH_Synchronizer::instance', $dependencies )
			     ->set_first_step( '@shop_retriever' );

			return $this;
		}

		public function set_consts() {
			$this->set_var('sync_start_time', time());
			$this->set_var('sync_interval', 'hourly');

			return $this;
		}

	}