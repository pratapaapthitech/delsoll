<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\FundRaiser;
use App\FundRaisersDescriptions;
use App\FundRaisersImages;
use App\Category;
use App\Country;
use App\Payment;
use App\Reward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class FundraisersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('app.start_a_fundraiser');
        $categories = Category::all();
        $countries = Country::all();

        return view('admin.start_fundraiser', compact('title', 'categories', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		// Validations
        $rules = [
            'fund_category_id'   => 'required',
            'fund_title'         => 'required',
            'fund_sub_title'     => 'required',
        ];
        $this->validate($request, $rules);
		// End
		// Save fundraiser information
        $user_id = Auth::user()->id;
		$fund_tax_exempt = 0;
		if($request->fund_tax_exempt == 'on'){
			$fund_tax_exempt = 1;
		}
		// Images uploads
		$image_fund_logo_image = "";
		$image_fund_banner_image = "";
		$image_fund_own_image = "";
        if ($request->file('fund_logo_image')){
            $image = $request->file('fund_logo_image');
            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Fundraisers Profile or Logo Image Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $upload_dir = './uploads/fundraisers/';
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $full_image = Image::make($image)->orientate()->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_fund_logo_image = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
            $imagePath = $upload_dir.$image_fund_logo_image;
            try{
                $full_image->save($imagePath);
            } catch (\Exception $e){
                return $e->getMessage();
            }
        }
		if ($request->file('fund_banner_image')){
            $image = $request->file('fund_banner_image');
            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Fundraisers Top Banner Image Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $upload_dir = './uploads/fundraisers/';
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $full_image = Image::make($image)->orientate()->resize(1500, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_fund_banner_image = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
            $imagePath = $upload_dir.$image_fund_banner_image;
            try{
                $full_image->save($imagePath);
            } catch (\Exception $e){
                return $e->getMessage();
            }
        }
		if ($request->file('fund_own_image')){
            $image = $request->file('fund_own_image');
            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Fundraisers Own Donate Image Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $upload_dir = './uploads/fundraisers/';
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $full_image = Image::make($image)->orientate()->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_fund_own_image = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
            $imagePath = $upload_dir.$image_fund_own_image;
            try{
                $full_image->save($imagePath);
            } catch (\Exception $e){
                return $e->getMessage();
            }
        }
		// End
        $data = [
            'user_id'      			=> $user_id,
            'fund_category_id'    	=> $request->fund_category_id,
            'fund_title'       		=> $request->fund_title,
            'fund_sub_title'       	=> $request->fund_sub_title,
            'fund_goal_ammount'     => $request->fund_goal_ammount,
            'fund_tax_exempt'       => $fund_tax_exempt,
            'fund_begin_type'       => $request->fund_begin_type,
            'fund_begin_date'       => $request->fund_begin_date,
            'fund_logo_image'       => $image_fund_logo_image,
            'fund_banner_image'     => $image_fund_banner_image,
            'fund_own_image'       	=> $image_fund_own_image,
            'created_at'       		=> date('Y-m-d H:i:s'),
            'updated_at'       		=> date('Y-m-d H:i:s'),
        ];
        $create = FundRaiser::create($data);
		// End
		// Save fundraiser descriptions
		if(count($request->fund_description_title) != 0){
			foreach($request->fund_description_title as $k=>$fd){
				if($fd != ""){
					$datad = [
						'fund_raiser_id'    => $create->id,
						'fd_title'    		=> $fd,
						'fd_description'    => $request->fund_description_description[$k],
						'created_at'       	=> date('Y-m-d H:i:s'),
						'updated_at'       	=> date('Y-m-d H:i:s'),
					];
					FundRaisersDescriptions::create($datad);
				}
			}
		}
		// End
		// Save slider imagesavealpha
		$fi_image_slider_images = '';
		if ($request->file('fi_image_slider')){
			foreach($request->file('fi_image_slider') as $fi_image_slider){
				$image = $fi_image_slider;
				$valid_extensions = ['jpg','jpeg','png'];
				if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
					return redirect()->back()->withInput($request->input())->with('error', 'Fundraisers slider Images Only .jpg, .jpeg and .png is allowed extension') ;
				}
				$upload_dir = './uploads/fundraisers/';
				$file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
				$full_image = Image::make($image)->orientate()->resize(500, null, function ($constraint) {
					$constraint->aspectRatio();
				});
				$fi_image_slider_images = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
				$imagePath = $upload_dir.$fi_image_slider_images;
				try{
					$full_image->save($imagePath);
					// Save to db
					$datad = [
						'fund_raiser_id'    => $create->id,
						'fi_image'    		=> $fi_image_slider_images,
						'created_at'       	=> date('Y-m-d H:i:s'),
						'updated_at'       	=> date('Y-m-d H:i:s'),
					];
					FundRaisersImages::create($datad);
					//End
				} catch (\Exception $e){
					return $e->getMessage();
				}
			}
        }
		// End
        if ($create){
			$user = request()->user();
			if($user->user_type == 'user'){
				return redirect(route('my_fundraisers'))->with('success', trans('app.campaign_created'));
			}else{
				return redirect(route('all_fundraisers'))->with('success', trans('app.campaign_created'));
			}
        }
        return back()->with('error', trans('app.something_went_wrong'))->withInput($request->input());
    }
    
    public function allFundraisers(){
        $title = trans('app.all_fundraisers');
		$user = request()->user();
		if($user->user_type == 'user'){
			$fundraisers = FundRaiser::whereUserId($user->id)->orderBy('id', 'desc')->paginate(20);
		}else{
			$fundraisers = FundRaiser::active()->orderBy('id', 'desc')->paginate(20);
		}
        return view('admin.all_fundraisers', compact('title', 'fundraisers'));
    }
	
	public function statusChange($id, $status = null){

        $fundraiser = FundRaiser::find($id);
        if ($fundraiser && $status){

            if ($status == 'approve'){
                $fundraiser->fund_status = 1;
                $fundraiser->save();

            }elseif($status == 'block'){
                $fundraiser->fund_status = 2;
                $fundraiser->save();
            }

        }
        return back()->with('success', trans('app.status_updated'));
    }
	
	public function deleteFundraisers($id = 0){
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }

        if ($id){
            $fundraiser = FundRaiser::find($id);
            if ($fundraiser){
                $fundraiser->delete();
            }
        }
        return back()->with('success', trans('app.fundraiser_deleted'));
    }
	
	public function deleteSliderImage($id = 0){
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }

        if ($id){
            $fundraiserImage = FundRaisersImages::find($id);
            if ($fundraiserImage){
                $fundraiserImage->delete();
            }
        }
        return back()->with('success', trans('app.slider_deleted'));
    }
	
	public function edit($id)
    {
        $user_id = request()->user()->id;
        $fundraiser = FundRaiser::find($id);
        //todo: checked if admin then he can access...
        // if ($fundraiser->user_id != $user_id){
            // exit('Unauthorized access');
        // }

        $title = trans('app.edit_fundraiser');
        $categories = Category::all();
        $countries = Country::all();
        $descriptions = FundRaisersDescriptions::where('fund_raiser_id', $id)->orderBy('fd_id', 'asc')->get();
        $sliders = FundRaisersImages::where('fund_raiser_id', $id)->orderBy('id', 'asc')->get();
		
        return view('admin.edit_fundraiser', compact('title', 'categories', 'countries', 'fundraiser', 'descriptions', 'sliders'));
    }
	
	public function update(Request $request, $id){

        // Validations
        $rules = [
            'fund_category_id'   => 'required',
            'fund_title'         => 'required',
            'fund_sub_title'     => 'required',
        ];
        $this->validate($request, $rules);
		// End
		// Save fundraiser information
        $user_id = Auth::user()->id;
		$fund_tax_exempt = 0;
		if($request->fund_tax_exempt == 'on'){
			$fund_tax_exempt = 1;
		}
		// Images uploads
		$image_fund_logo_image = $request->hid_fund_logo_image;
		$image_fund_banner_image = $request->hid_fund_banner_image;
		$image_fund_own_image = $request->hid_fund_own_image;
        if ($request->file('fund_logo_image')){
            $image = $request->file('fund_logo_image');
            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Fundraisers Profile or Logo Image Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $upload_dir = './uploads/fundraisers/';
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $full_image = Image::make($image)->orientate()->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_fund_logo_image = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
            $imagePath = $upload_dir.$image_fund_logo_image;
            try{
                $full_image->save($imagePath);
            } catch (\Exception $e){
                return $e->getMessage();
            }
        }
		if ($request->file('fund_banner_image')){
            $image = $request->file('fund_banner_image');
            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Fundraisers Top Banner Image Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $upload_dir = './uploads/fundraisers/';
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $full_image = Image::make($image)->orientate()->resize(1500, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_fund_banner_image = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
            $imagePath = $upload_dir.$image_fund_banner_image;
            try{
                $full_image->save($imagePath);
            } catch (\Exception $e){
                return $e->getMessage();
            }
        }
		if ($request->file('fund_own_image')){
            $image = $request->file('fund_own_image');
            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Fundraisers Own Donate Image Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $upload_dir = './uploads/fundraisers/';
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $full_image = Image::make($image)->orientate()->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_fund_own_image = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
            $imagePath = $upload_dir.$image_fund_own_image;
            try{
                $full_image->save($imagePath);
            } catch (\Exception $e){
                return $e->getMessage();
            }
        }
		// End
        $data = [
            'user_id'      			=> $user_id,
            'fund_category_id'    	=> $request->fund_category_id,
            'fund_title'       		=> $request->fund_title,
            'fund_sub_title'       	=> $request->fund_sub_title,
            'fund_goal_ammount'     => $request->fund_goal_ammount,
            'fund_tax_exempt'       => $fund_tax_exempt,
            'fund_begin_type'       => $request->fund_begin_type,
            'fund_begin_date'       => $request->fund_begin_date,
            'fund_logo_image'       => $image_fund_logo_image,
            'fund_banner_image'     => $image_fund_banner_image,
            'fund_own_image'       	=> $image_fund_own_image,
            'updated_at'       		=> date('Y-m-d H:i:s'),
        ];
		$update = FundRaiser::whereId($id)->update($data);
		// End
		// Add fundraiser descriptions
		if(count($request->fund_description_title) != 0){
			$fundraiserd = FundRaisersDescriptions::where('fund_raiser_id',$id);
            if ($fundraiserd){
                $fundraiserd->delete();
            }
			foreach($request->fund_description_title as $k=>$fd){
				if($fd != ""){
					$datad = [
						'fund_raiser_id'    => $id,
						'fd_title'    		=> $fd,
						'fd_description'    => $request->fund_description_description[$k],
						'created_at'       	=> date('Y-m-d H:i:s'),
						'updated_at'       	=> date('Y-m-d H:i:s'),
					];
					FundRaisersDescriptions::create($datad);
				}
			}
		}
		// End
		// Save slider imagesavealpha
		$fi_image_slider_images = '';
		if ($request->file('fi_image_slider')){
			foreach($request->file('fi_image_slider') as $fi_image_slider){
				$image = $fi_image_slider;
				$valid_extensions = ['jpg','jpeg','png'];
				if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
					return redirect()->back()->withInput($request->input())->with('error', 'Fundraisers slider Images Only .jpg, .jpeg and .png is allowed extension') ;
				}
				$upload_dir = './uploads/fundraisers/';
				$file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
				$full_image = Image::make($image)->orientate()->resize(500, null, function ($constraint) {
					$constraint->aspectRatio();
				});
				$fi_image_slider_images = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
				$imagePath = $upload_dir.$fi_image_slider_images;
				try{
					$full_image->save($imagePath);
					// Save to db
					$datad = [
						'fund_raiser_id'    => $id,
						'fi_image'    		=> $fi_image_slider_images,
						'created_at'       	=> date('Y-m-d H:i:s'),
						'updated_at'       	=> date('Y-m-d H:i:s'),
					];
					FundRaisersImages::create($datad);
					//End
				} catch (\Exception $e){
					return $e->getMessage();
				}
			}
        }
		// End
        if ($update){
            $user = request()->user();
			if($user->user_type == 'user'){
				return redirect(route('my_fundraisers'))->with('success', trans('app.campaign_created'));
			}else{
				return redirect(route('all_fundraisers'))->with('success', trans('app.campaign_created'));
			}
        }
        return back()->with('error', trans('app.something_went_wrong'))->withInput($request->input());
    }
}
