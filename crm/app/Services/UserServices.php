<?php
namespace App\Services;
use App\Users;

class UserServices
{
	public static function doLogin($user, $pass)
	{
		$result = Users::getUserDetails($user, 1);
	}
}