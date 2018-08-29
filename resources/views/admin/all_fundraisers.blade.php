@extends('layouts.app')

@section('title') @if(! empty($title)) {{$title}} @endif - @parent @endsection

@section('content')


    <div class="dashboard-wrap">
        <div class="container">
            <div id="wrapper">

                @include('admin.menu')

                <div id="page-wrapper">

                    @if( ! empty($title))
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header"> {{ $title }}  </h1>
                            </div> <!-- /.col-lg-12 -->
                        </div> <!-- /.row -->
                    @endif

                    @include('admin.flash_msg')

                       <div class="admin-campaign-lists">
                           <div class="row">
                               <div class="col-md-5">
                                   @lang('app.total') : {{$fundraisers->count()}}
                               </div>

                               <div class="col-md-7">

                                   <form class="form-inline" method="get" action="{{route('campaign_admin_search')}}">
                                       <div class="form-group">
                                           <input type="text" name="q" value="{{request('q')}}" class="form-control" placeholder="@lang('app.fundraiser_title_keyword')">
                                       </div>
                                       <button type="submit" class="btn btn-default">@lang('app.search')</button>
                                   </form>

                               </div>
                           </div>
                       </div>

                    @if($fundraisers->count() > 0)
                        <table class="table table-striped table-bordered">

                            <tr>
                                <th>@lang('app.image')</th>
                                <th>@lang('app.title')</th>
                                <th>@lang('app.fundraiser_info')</th>
                                <th>@lang('app.owner_info')</th>
                                <th>@lang('app.actions')</th>
                            </tr>

                            @foreach($fundraisers as $fundraiser)

                                <tr>

                                    <td width="70"><img src="{{$fundraiser->fund_banner_image_url()}}" class="img-responsive" /></td>
                                    <td>{{$fundraiser->fund_title}}</td>
                                    <td>
                                        @lang('app.goal') : {{get_amount($fundraiser->fund_goal_ammount)}} <br />
                                        @lang('app.raised') :  {{get_amount($fundraiser->success_payments->sum('amount'))}} <br />
                                        @lang('app.raised_percent') : {{$fundraiser->percent_raised()}}%<br />
                                        @lang('app.backers') : {{$fundraiser->success_payments->count()}}<br />
                                    </td>

                                    <td>
                                        <strong>{{$fundraiser->user->name}}</strong> 
                                    </td>

                                    <td>
									<a href="{{route('edit_fundraiser', $fundraiser->id)}}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> </a>
									
                                    @if($fundraiser->fund_status == 0)
                                            <a href="{{route('fundraiser_status', [$fundraiser->id, 'approve'])}}" class="btn btn-success btn-sm" data-toggle="tooltip" title="@lang('app.approve')"><i class="fa fa-check-circle-o"></i> </a>
                                            <a href="{{route('fundraiser_status', [$fundraiser->id, 'block'])}}" class="btn btn-danger btn-sm" data-toggle="tooltip" title="@lang('app.block')"><i class="fa fa-ban"></i> </a>


                                        @elseif($fundraiser->fund_status == 1)

                                            <a href="{{route('fundraiser_status', [$fundraiser->id, 'block'])}}" class="btn btn-danger btn-sm" data-toggle="tooltip" title="@lang('app.block')"><i class="fa fa-ban"></i> </a>

                                        @elseif($fundraiser->fund_status == 2)
                                            <a href="{{route('fundraiser_status', [$fundraiser->id, 'approve'])}}" class="btn btn-success btn-sm" data-toggle="tooltip" title="@lang('app.approve')"><i class="fa fa-check-circle-o"></i> </a>
                                        @endif
										
                                        <a href="{{route('fundraiser_delete', $fundraiser->id)}}" class="btn btn-delete btn-danger btn-sm" data-toggle="tooltip" title="@lang('app.delete')"><i class="fa fa-trash-o"></i> </a>


                                    </td>

                                </tr>

                            @endforeach

                        </table>

                        {!! $fundraisers->links() !!}
                    @else
                        @lang('app.no_fundraisers_to_display')
                    @endif

                </div>

            </div>
        </div>
    </div>


@endsection

@section('page-js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn-delete').click(function(e){
                if (! confirm("@lang('app.are_you_sure_undone')") ){
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection