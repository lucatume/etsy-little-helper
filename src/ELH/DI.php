<?php


	class ELH_DI {

		/**
		 * @var tad_DI52_Container
		 */
		protected static $container;

		/**
		 * @return tad_DI52_Container
		 */
		public static function instance() {
			if ( empty( self::$container ) ) {
				self::$container = new tad_DI52_Container();
			}

			return self::$container;
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
			$this->set_ctor( 'listings_retriever', 'ELH_ListingsRetriever::instance', $dependencies );
			$this->set_ctor( 'shop_retriever', 'ELH_ShopRetriever::instance', $dependencies )
			     ->set_next( '@listings_retriever' );

			// Sync set up
			$dependencies = array( '@sync_strategy_selector' );
			$this->set_ctor( 'synchronizer', 'ELH_Synchronizer::instance', $dependencies )
			     ->set_first_step( '@shop_retriever' );

			return $this;
		}

	}