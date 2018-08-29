<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FundRaiser extends Model
{
	public $guarded = [];
   
	public function scopeActive($query){
        return $query->where('fund_status', 1);
    }
	
	public function fund_banner_image_url($full_size = false){
		if ($this->fund_banner_image){
			return asset('uploads/fundraisers/'.$this->fund_banner_image);
		}else{
			return asset('assets/images/campaign-placeholder.jpg');
		}
	}
	
	public function fund_logo_image_url($full_size = false){
		if ($this->fund_logo_image){
			return asset('uploads/fundraisers/'.$this->fund_logo_image);
		}else{
			return asset('assets/images/campaign-placeholder.jpg');
		}
	}
	
	public function fund_own_image_url($full_size = false){
		if ($this->fund_own_image){
			return asset('uploads/fundraisers/'.$this->fund_own_image);
		}else{
			return asset('assets/images/campaign-placeholder.jpg');
		}
	}
	
	public function success_payments(){
        return $this->hasMany(Payment::class)->whereStatus('success');
    }
	
	public function percent_raised(){
        $raised = $this->success_payments()->sum('amount');
        $goal = $this->fund_goal_ammount;

        $percent = 0;
        if ($raised > 0){
            $percent = round(($raised * 100) / $goal, 0, PHP_ROUND_HALF_DOWN);
        }
        return $percent;
    }
	
	public function user(){
        return $this->belongsTo(User::class);
    }

	public function days_left(){
        $diff = strtotime($this->end_date)-time();//time returns current time in seconds

        if ($diff > 0){
            return floor($diff/(60*60*24));//seconds/minute*minutes/hour*hours/day)
        }
        return 0;
    }
}
