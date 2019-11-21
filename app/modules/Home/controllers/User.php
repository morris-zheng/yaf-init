<?php

class UserController extends BaseHomeController
{
	public function init()
	{
		parent::init();
	}

	public function findAction()
	{
		$user_service = new UserService();
		if (false == $user_service->find(123)) {
			return Response::fail($user_service->getMessage(), $user_service->getCode());
		}
		return Response::success();
	}
}
