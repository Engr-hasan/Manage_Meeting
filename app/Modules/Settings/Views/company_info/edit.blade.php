@extends('layouts.admin')

@section('page_heading',trans('messages.area_list'))

@section('content')

    <div class="col-lg-12">
        <section class="col-md-12" id="printDiv">
            <div class="row"><!-- Horizontal Form -->
                <div class="col-lg-12">
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="user_edit_form">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Company Details of : {{$companyDetails->company_name}}</h3>
                                </div> <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-9">
                                        <dl class="dls-horizontal">

                                            <dt>Company Name :</dt>
                                            <dd>{{$companyDetails->company_info}}&nbsp;</dd>
                                            <dt>Status :</dt>
                                            <dd>
                                                @if($companyDetails->is_archived==0)
                                                    {{ $companyDetails->is_approved == 1 ? 'Approved' : 'Not Approved Yet' }}</dd>
                                                @else
                                                    Rejected
                                                @endif
                                            <dt>Created By :</dt>
                                            <dd>{{ $companyDetails->user_full_name }}</dd>
                                        </dl>
                                    </div>
                                </div><!-- /.box -->
                            </div>

                            <div class="col-md-12">

                                <div class="pull-left">
                                    <a href="{{ url('/settings/company-info') }}" class="btn btn-sm btn-default"><i class="fa fa-times"></i> Close</a>
                                </div>
                                <div class="pull-right">
                                    @if($companyDetails->is_approved=='0' && $companyDetails->is_archive=='0')
                                    <a href="{{URL::to('settings/approved-change-status/'.\App\Libraries\Encryption::encodeId($companyDetails->id))}}" class="btn btn-success"
                                       onclick="return confirm('Are you sure?')"><i class="fa fa-unlock-alt"></i> Make Approved </a>

                                        <a href="{{URL::to('settings/rejected-change-status/'.\App\Libraries\Encryption::encodeId($companyDetails->id))}}" class="btn btn-danger"
                                           onclick="return confirm('Are you sure?')"><i class="fa fa-remove"></i> {{$companyDetails->is_approved == 0 ? 'Rejected ' : ''}}</a>
                                </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>


    </div>

@endsection