<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            House Entry Verification of Agency : {!! $json_object->agency_name !!}
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        House Information
                    </div>
                </div>
                <div class="panel-body" style="min-height: 330px">
                    <div class="col-md-12">
                        {!! Form::label('title','House No : ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->house_no }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','House Name : ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->house_name }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','House Category : ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->house_category }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','House Capacity : ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->house_capacity }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Permanent Housing: ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->permanent_housing }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Remarks : ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->remarks }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Notice : ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->notice }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','First Lease : ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->first_lease }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','E-hajj id : ',['class'=>'col-md-5']) !!}
                        <div class="col-md-7">{{ $json_object->e_hajj_id }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        House Location and Others Information
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        {!! Form::label('title','House Area : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->house_area }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Area : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->area }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','City : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->city }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Other City : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->other_city }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Haram Sharif distance: ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->haram_sharif_distance }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Floor : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->floor }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','No of room : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->no_of_room }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Lift : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->lift }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Tasriah : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->tasriah }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Muallem : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->muallem }}</div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('title','Arabic house address : ',['class'=>'col-md-6']) !!}
                        <div class="col-md-6">{{ $json_object->arabic_house_address }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-green">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h5><strong>গুগল ম্যাপের রেফারেন্স :</strong></h5>
                    </div>
                    <div style="display: none">
                        <div class="form-group col-md-6 {{$errors->has('latitude') ? 'has-error' : ''}}">
                            {!! Form::label('latitude','অক্ষাংশ: ',['class'=>'col-md-3 font-ok']) !!}
                            <div class="col-md-6">
                                {!! Form::text('latitude',$json_object->latitude, ['class'=>'form-control bnEng input-sm','placeholder'=>'Latitude',
                                'data-rule-maxlength'=>'40','id'=>'us3-lat']) !!}
                                {!! $errors->first('latitude','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group col-md-6 {{$errors->has('longitude') ? 'has-error' : ''}}">
                            {!! Form::label('longitude','দ্রাঘিমাংশ: ',['class'=>'col-md-3 font-ok']) !!}
                            <div class="col-md-6">
                                {!! Form::text('longitude',$json_object->longitude, ['class'=>'form-control bnEng input-sm','placeholder'=>'Longitude',
                                'data-rule-maxlength'=>'40','id'=>'us3-lon']) !!}
                                {!! $errors->first('longitude','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div id="us3" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('footer-script2')
        {{--To rander loaction map--}}
        <script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{env('GOOGLE_MAP')}}&libraries=places'></script>
        <script src="{{ asset("assets/scripts/locationpicker.jquery.min.js") }}" src="" type="text/javascript"></script>
        <script>
            var latlng = {
                latitude: '{{ $json_object->latitude != null ? $json_object->latitude : 21.4229783}}',
                longitude:  '{{ $json_object->longitude!=null ? $json_object->longitude : 39.8255956 }}'
            };

            $('#us3').locationpicker({
                location: {
                    latitude: latlng.latitude,
                    longitude: latlng.longitude
                },
                radius: 0,
                inputBinding: {
                    latitudeInput: $('#us3-lat'),
                    longitudeInput: $('#us3-lon'),
                    radiusInput: $('#us3-radius'),
                    locationNameInput: $('#us3-address')
                },
                enableAutocomplete: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    // Uncomment line below to show alert on each Location Changed event
                    //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                }
            });
            $(".map_locations").on('click',function(){
                $('#us3-lat').val('');
                $('#us3-lon').val('');


                var lat = $(this).data('lat');
                var lng = $(this).data('lng');

                $('#us3-lat').val(lat);
                $('#us3-lon').val(lng);
                $('#us3').locationpicker({
                    location: {
                        latitude: lat,
                        longitude: lng
                    },
                    radius: 0,
                    inputBinding: {
                        latitudeInput: $('#us3-lat'),
                        longitudeInput: $('#us3-lon'),
                        radiusInput: $('#us3-radius'),
                        locationNameInput: $('#us3-address')
                    },
                    enableAutocomplete: true,
                    onchanged: function (currentLocation, radius, isMarkerDropped) {
                        // Uncomment line below to show alert on each Location Changed event
                        //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                    }
                });
            });
        </script>
@endsection