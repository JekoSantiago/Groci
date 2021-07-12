<?php

namespace App\Services;

class TextServices
{
    public static function message($i)
	{
		$error = [
            100  => 'You have successfully registered on our website. Please visit your preferred store to confirm your registration. Kindly present the confirmation code below to the cashier.<p class="code-txt-center">[code]</p>',  
            101  => "You successfully re-ordered items. Please check your basket if you want to remove some items. For adding more items, just continue shopping.",
            102  => "Thank you for registering with Groci. Please check your email to activate your account.",
            103  => 'You have successfully registered another store on your account. You may sign-in using your registered username and password.',
            -1   => 'User account does not exist. Try another one!',
            -3   => 'Username or password is invalid',
            -2   => 'Address type "[add-type]" already been registered. Try again!',
            -4   => 'ERROR: Unable to update customer address. Please try again!', 
            -5   => 'ERROR: Unable to save order details. Please try again!', 
            -6   => 'SERVER ERROR: Unable to send notification.',
            -100 => 'ERROR: Unable to save data due to server error. Try again later!',
            -101 => 'ERROR: Unable to update data due to server error. Try again later!',
            -200 => 'You already registered with [store]. You may sign-in using your registered username and password.'
        ];

		return $error[$i];
    }
}