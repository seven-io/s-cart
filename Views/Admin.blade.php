@extends($templatePathAdmin.'layout')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h2 class="card-title">{{ $title_description ?? '' }}</h2>
                </div>

                <div class="card-body table-responsivep-0">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th width="40%">{{ sc_language_render($pathPlugin.'::lang.api_key') }}</th>
                                <td>
                                    <a
                                            class="updateData_can_empty editable editable-click"
                                            data-name="Seven_api_key"
                                            data-pk="Seven_api_key"
                                            data-title="{{ sc_language_render($pathPlugin.'::lang.api_key') }}"
                                            data-type="text"
                                            data-url="{{ sc_route_admin('admin_config_global.update') }}"
                                            data-value="{{ (sc_admin_can_config()) ? sc_config('Seven_api_key'): 'hidden' }}"
                                            href="#"
                                    ></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <link rel="stylesheet" href="{{ sc_file('admin/plugin/bootstrap-editable.css')}}">
    <style>
        #maintain_content img {
            max-width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ sc_file('admin/plugin/bootstrap-editable.min.js')}}"></script>

    <script>
        $(document).ready(function() {

            $.fn.editable.defaults.params = function(params) {
                params._token = "{{ csrf_token() }}"
                return params
            }
            $('.fied-required').editable({
                validate: function(value) {
                    if (value == '') return '{{  sc_language_render('admin.not_empty') }}'
                },
                success: function(data) {
                    if (data.error == 0) alertJs('success', '{{ sc_language_render('admin.msg_change_success') }}')
                    else alertJs('error', data.msg)
                },
            })

            $('.updateData_can_empty').editable({
                success: function(data) {
                    console.log(data)
                    if (data.error == 0)
                        alertJs('success', '{{ sc_language_render('admin.msg_change_success') }}')
                    else alertJs('error', data.msg)
                },
            })

        })
    </script>
@endpush
