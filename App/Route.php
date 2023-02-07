<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['geraRifa'] = array(
			'route' => '/geraRifa',
			'controller' => 'AppController',
			'action' => 'geraRifa'
		);

		$routes['baixar'] = array(
			'route' => '/baixar',
			'controller' => 'AppController',
			'action' => 'baixar'
		);

		$routes['realizarSorteio'] = array(
			'route' => '/realizarSorteio',
			'controller' => 'AppController',
			'action' => 'realizarSorteio'
		);

		$routes['sortear'] = array(
			'route' => '/sortear',
			'controller' => 'AppController',
			'action' => 'sortear'
		);

		$routes['verificarResultado'] = array(
			'route' => '/verificarResultado',
			'controller' => 'AppController',
			'action' => 'verificarResultado'
		);

		$this->setRoutes($routes);
	}

}

?>