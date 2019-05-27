@if($dbMode!='PRODUCTION')
    <div class="row">
        <span class="text-danger huge">You are connected to {!! $dbMode !!} Database!</span>
    </div>
@endif