
<link rel="stylesheet" href="{{ asset('/assets/css/vendor.bundle.base.css') }}" async>

<link rel="stylesheet" href="{{ asset('/assets/fonts/font-awesome.min.css') }}" async/>
<!-- Material Design Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
<link rel="stylesheet" href="{{ asset('/assets/select2/select2.min.css') }}" async>
<link rel="stylesheet" href="{{ asset('/assets/jquery-toast-plugin/jquery.toast.min.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/color-picker/color.min.css') }}" async>

<link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">


<link rel="stylesheet" href="{{ asset('/assets/css/datepicker.min.css') }}" async>
<link rel="stylesheet" href="{{ asset('/assets/css/ekko-lightbox.css') }}">

<link rel="stylesheet" href="{{ asset('/assets/bootstrap-table/bootstrap-table.min.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/bootstrap-table/fixed-columns.min.css') }}">

<link rel="shortcut icon" href="" />
<link rel="shortcut icon" href=""/>
<link rel="stylesheet" type="text/css" href="{{URL::asset('app-assets/vendors/css/vendors.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('app-assets/vendors/css/forms/selects/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('app-assets/vendors/css/extensions/toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('app-assets/css/plugins/extensions/toastr.css')}}">

<link rel="stylesheet" type="text/css" href="{{URL::asset('vendor/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/fonts/line-awesome/css/line-awesome.css') }}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('vendor/jquery-ui/jquery-ui.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('vendor/jquery-confirm/dist/jquery-confirm.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('vendor/datatables-responsive/dataTables.responsive.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('vendor/datatables/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}">
<style>

</style>
<script>
    var baseUrl = "{{ URL::to('/') }}";
    const onErrorImage = (e) => {
        e.target.src = "{{ asset('/storage/no_image_available.jpg') }}";
    };
</script>
