<?php

namespace App\Http\Controllers;

use App\Country;
use App\Option;
use App\User;
use App\UserStripe;
use App\Withdrawal_preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    public function index(){
        $title = trans('app.users');
        $users = User::orderBy('name', 'asc')->paginate(20);
        $users_count = User::count();
        return view('admin.users', compact('title', 'users', 'users_count'));
    }

    public function show($id = 0){
        if ($id){
            $title = trans('app.profile');
            $user = User::find($id);

            $is_user_id_view = true;
            return view('admin.profile', compact('title', 'user', 'is_user_id_view'));
        }
    }

    /**
     * @param $id
     * @param null $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusChange($id, $status = null){
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }

        $user = User::find($id);
        if ($user && $status){
            if ($status == 'approve'){
                $user->active_status = 1;
                $user->save();

            }elseif($status == 'block'){
                $user->active_status = 2;
                $user->save();
            }
        }
        return back()->with('success', trans('app.status_updated'));
    }



    public function profile(){
        $title = trans('app.profile');
        $user = Auth::user();
        return view('admin.profile', compact('title', 'user'));
    }

    public function profileEdit($id = null){
        $title = trans('app.profile_edit');
        $user = Auth::user();

        if ($id){
            $user = User::find($id);
        }

        $countries = Country::all();

        return view('admin.profile_edit', compact('title', 'user', 'countries'));
    }

    public function profileEditPost($id = null, Request $request){
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }

        $user = Auth::user();
        if ($id){
            $user = User::find($id);
        }
        //Validating
        $rules = [
            'email'    => 'required|email|unique:users,email,'.$user->id,
        ];
        $this->validate($request, $rules);

        $inputs = array_except($request->input(), ['_token', 'photo']);
        $user->update($inputs);

        if ($request->hasFile('photo')){
            $rules = ['photo'=>'mimes:jpeg,jpg,png'];
            $this->validate($request, $rules);

            $image = $request->file('photo');
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());


            $resized = Image::make($image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image_name = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $upload_dir = './uploads/avatar/';
            if ( ! file_exists($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }

            $imageFileName = $upload_dir.$image_name;

            try{
                //Uploading thumb
                $resized->save($imageFileName);

                $previous_photo= $user->photo;
                $user->photo = $image_name;
                $user->save();

                if ($previous_photo){
                    if (file_exists($upload_dir.$previous_photo)){
                        unlink($upload_dir.$previous_photo);
                    }
                }

            } catch (\Exception $e){
                return $e->getMessage();
            }

        }

        return back()->with('success', trans('app.profile_edit_success_msg'));
    }

	public function withdrawalPreferenceStripe(){
		
		$options = Option::all();
		$stripeSecreateL = "";
		$stripeSecreateT = "";
		$stripeTestMode = "";
		foreach($options as $op){
			if($op->option_key == 'stripe_test_mode'){
				$stripeTestMode = $op->option_value;
			}
			if($op->option_key == 'stripe_test_secret_key'){
				$stripeSecreateT = $op->option_value;
			}
			if($op->option_key == 'stripe_live_secret_key'){
				$stripeSecreateL = $op->option_value;
			}
		}
		$secreateKey = $stripeSecreateT;
		if($stripeTestMode == '0'){
			$secreateKey = $stripeSecreateL;
		}
		//echo $secreateKey; exit;
		
		$authStripeCode = $_GET['code'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://connect.stripe.com/oauth/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "client_secret=".$secreateKey."&code=".$authStripeCode."&grant_type=authorization_code");
		curl_setopt($ch, CURLOPT_POST, 1);

		$headers = array();
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close ($ch);
		
		$finalResult = json_decode($result);
		//echo '<pre>'; print_r($finalResult); exit;
		
		$title = trans('app.withdrawal_preference_stripe');
        $user_id = Auth::user()->id;
		
		$data = [
            'user_id'    				=> $user_id,
            'stripe_user_id'            => $finalResult->stripe_user_id,
            'token_type'     			=> $finalResult->token_type,
            'stripe_publishable_key'    => $finalResult->stripe_publishable_key,
            'scope'                    	=> $finalResult->scope,
            'livemode'                	=> $finalResult->livemode,
            'refresh_token'             => $finalResult->refresh_token,
            'access_token'              => $finalResult->access_token,
            'created_at'           		=> date('Y-m-d H:i:s'),
            'updated_at'                => date('Y-m-d H:i:s'),
        ];
		
		UserStripe::create($data);
		
		$stripeid = $finalResult->stripe_user_id;
        return view('admin.withdrawal_preference_stripe', compact('title', 'stripeid'));
    }

    public function withdrawalPreference(){
		$userStripeAccountId = "";
        $title = trans('app.withdrawal_preference');
        $user = Auth::user();

        $countries = Country::all();
        $options = Option::all();
		
		$stripeClientIdT = "";
		$stripeClientIdL = "";
		$stripeRedirectUrlT = "";
		$stripeRedirectUrlL = "";
		$stripeTestMode = "";
		foreach($options as $op){
			if($op->option_key == 'stripe_test_mode'){
				$stripeTestMode = $op->option_value;
			}
			if($op->option_key == 'stripe_test_client_id'){
				$stripeClientIdT = $op->option_value;
			}
			if($op->option_key == 'stripe_test_redirect_url'){
				$stripeRedirectUrlT = $op->option_value;
			}
			if($op->option_key == 'stripe_live_client_id'){
				$stripeClientIdL = $op->option_value;
			}
			if($op->option_key == 'stripe_live_redirect_url'){
				$stripeRedirectUrlL = $op->option_value;
			}
		}
		
		$stripedetails = UserStripe::whereUserId($user->id)->first();
		if(isset($stripedetails->stripe_user_id)){
			$userStripeAccountId = $stripedetails->stripe_user_id;
		}
        return view('admin.withdrawal_preference', compact('title', 'user', 'countries', 'userStripeAccountId', 'options', 'stripeClientIdT', 'stripeRedirectUrlT', 'stripeClientIdL', 'stripeRedirectUrlL', 'stripeTestMode'));
    }

    public function withdrawalPreferenceUpdate(Request $request){
        $user_id = Auth::user()->id;
        $rules = [
            'default_withdrawal_account'    =>'required'
        ];
        $this->validate($request, $rules);

        $data = [
            'default_withdrawal_account'    => $request->default_withdrawal_account,
            'paypal_email'                  => $request->paypal_email,
            'bank_account_holders_name'     => $request->bank_account_holders_name,
            'bank_account_number'           => $request->bank_account_number,
            'swift_code'                    => $request->swift_code,
            'bank_name_full'                => $request->bank_name_full,
            'bank_branch_name'              => $request->bank_branch_name,
            'bank_branch_city'              => $request->bank_branch_city,
            'bank_branch_address'           => $request->bank_branch_address,
            'country_id'                    => $request->country_id,
            'user_id'                       => $user_id,
        ];

        $withdrawal_preference = Withdrawal_preference::whereUserId($user_id)->first();
        if ($withdrawal_preference){
            $withdrawal_preference->update($data);
        }else{
            Withdrawal_preference::create($data);
        }

        return redirect()->back()->with('success', trans('app.changes_has_been_saved'));

    }

    public function changePassword()
    {
        $title = trans('app.change_password');
        return view('admin.change_password', compact('title'));
    }

    public function changePasswordPost(Request $request)
    {
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }
        $rules = [
            'old_password'  => 'required',
            'new_password'  => 'required|confirmed',
            'new_password_confirmation'  => 'required',
        ];
        $this->validate($request, $rules);

        $old_password = $request->old_password;
        $new_password = $request->new_password;
        //$new_password_confirmation = $request->new_password_confirmation;

        if(Auth::check())
        {
            $logged_user = Auth::user();

            if(Hash::check($old_password, $logged_user->password))
            {
                $logged_user->password = Hash::make($new_password);
                $logged_user->save();
                return redirect()->back()->with('success', trans('app.password_changed_msg'));
            }
            return redirect()->back()->with('error', trans('app.wrong_old_password'));
        }
    }
    
    

}
