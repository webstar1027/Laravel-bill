<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Session;
use Auth;

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function __construct()
	{
        
		if ( ! empty($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}