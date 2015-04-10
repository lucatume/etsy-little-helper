<?php


	class ELH_DI {

		/**
		 * @var tad_DI52_Container
		 */
		protected static $container;

		public static function instance() {
			if ( empty( self::$container ) ) {
				self::$container = new tad_DI52_Container();
			}

			return self::$container;
		}

		public static function set_dependencies() {
			$container = self::instance();

			$container->set_var( 'sync_retry_interval', 3600 );
			$container->set_var( 'sync_retry_attempts', 3 );

			$container->set_ctor( 'keychain', 'ELH_Keychain' );
			$container->set_ctor( 'api', 'ELH_Api' );

			$args = array( '@keychain', '@api' );
			$container->set_ctor( 'shop_retriever', 'ELH_ShopRetriever::instance', $args );

			$args = array( '@keychain', '@api' );
			$container->set_ctor( 'listings_retriever', 'ELH_ListingsRetriever::instance', $args );

			$container->set_ctor( 'synchronizer', 'ELH_Synchronizer::instance', $args )
			          ->add_step( '@shop_retriever' )
			          ->add_step( '@listings_retriever' )
			          ->set_retry_interval( '#sync_retry_interval' )
			          ->set_retry_attempts( '#sync_retry_attempts' )
			          ->set_hook( ELH_Main::SYNC_HOOK );
		}

	}