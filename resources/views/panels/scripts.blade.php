<script>
var assetBaseUrl = "{{ asset('/') }}";
</script>

<script src="{{asset('vendors/js/vendors.min.js')}}"></script>
<script src="{{asset('fonts/LivIconsEvo/js/LivIconsEvo.tools.js')}}"></script>
<script src="{{asset('fonts/LivIconsEvo/js/LivIconsEvo.defaults.js')}}"></script>
<script src="{{asset('fonts/LivIconsEvo/js/LivIconsEvo.min.js')}}"></script>

@yield('vendor-scripts')

@if($configData['mainLayoutType'] == 'vendor')

<script src="{{asset('js/scripts/configs/vertical-menu-light.js')}}"></script>
<script>
var base_message_url = "{{ url('/vendor') }}";
</script>
@elseif($configData['mainLayoutType'] == 'customer')
<script src="{{asset('js/scripts/configs/vertical-menu-light.js')}}"></script>
<script>
var base_message_url = "{{ url('/customer') }}";
</script>
@else
<script src="{{asset('js/scripts/configs/horizontal-menu.js')}}"></script>
<script>
var base_message_url = "{{ url('/admin') }}";
</script>
@endif
<script src="{{asset('js/core/app-menu.js')}}"></script>
<script src="{{asset('js/core/app.js')}}"></script>
<script src="{{asset('js/scripts/components.js')}}"></script>
<script src="{{asset('js/scripts/footer.js')}}"></script>
<script src="{{asset('js/scripts/customizer.js')}}"></script>
<script src="{{asset('assets/js/scripts.js')}}"></script>

@yield('page-scripts')