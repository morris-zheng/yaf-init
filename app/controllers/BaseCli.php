<?php


class BaseCliController extends BaseController
{
	public function init()
	{
		parent::init();
		$this->disableView();
	}
}
