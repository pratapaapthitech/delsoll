<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FundRaisersImages extends Model
{
	public $guarded = [];
	
	public function fi_image_url($full_size = false){
		if ($this->fi_image){
			return asset('uploads/fundraisers/'.$this->fi_image);
		}else{
			return asset('assets/images/campaign-placeholder.jpg');
		}
	}
}
