<?php

class Controller_Welcome extends Controller
{
	public function action_index()
	{
		return Response::forge(View::forge('welcome/index'));
	}

	public function action_hello()
	{
		return Response::forge(Presenter::forge('welcome/hello'));
	}

	public function action_404()
	{
		$messages = array('Aw, crap!', 'Bloody Hell!', 'Uh Oh!', 'Nope, not here.', 'Huh?');
		$this->title = $messages[array_rand($messages)];
		return Response::forge(View::forge('welcome/404', ['title' => $this->title]));
	}
}
