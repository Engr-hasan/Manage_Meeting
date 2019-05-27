@extends('layouts.admin')

@section('title')
    <title>Settings</title>
@endsection
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>
@section('content')
    <section class="content">

        <div class="row">
            <div class="col-md-12">

                <div class="col-md-6">
                    <!-- Application buttons -->
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Setting Buttons</h3>
                        </div>
                        <div class="box-body">
                            <a class="btn btn-app">
                                <span class="badge bg-green">12</span>
                                <i class="fa fa-envelope"></i> Submitted Application
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-yellow">3</span>
                                <i class="fa fa-bullhorn"></i> Notifications
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-green">300</span>
                                <i class="fa fa-barcode"></i> Products
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-purple">891</span>
                                <i class="fa fa-users"></i> Users
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-teal">67</span>
                                <i class="fa fa-inbox"></i> Orders
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-aqua">12</span>
                                <i class="fa fa-envelope"></i> Inbox
                            </a>
                            <a class="btn btn-app">
                                <span class="badge bg-red">531</span>
                                <i class="fa fa-heart-o"></i> Likes
                            </a>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /. row -->
    </section>
@endsection
