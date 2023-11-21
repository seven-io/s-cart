@extends($templatePathAdmin . 'layout')

@section('main')
    @if (isset($response))
        <div class='alert alert-light' role='alert'>
            <pre>{!! $response !!}</pre>
        </div>
    @endif

    <div class='card'>
        <div class='card-header with-border'>
            <h2 class='card-title'>
                {{ sc_language_render($pathPlugin.'::lang.sms.bulk.heading') }}
            </h2>
        </div>

        <form
                action='{{sc_route_admin('admin_seven.bulk_sms')}}'
                class='form-horizontal'
                method='post'
        >
            <div class='card-body'>
                <p>{{ sc_language_render($pathPlugin.'::lang.sms.bulk.intro') }}</p>

                <fieldset>
                    <legend>{{ sc_language_render($pathPlugin.'::lang.filters.legend') }}</legend>

                    <div class='form-group row'>
                        <label for='seven_filter_only_enabled' class='col-sm-2 col-form-label'>
                            {{ sc_language_render($pathPlugin.'::lang.filters.status.label') }}
                        </label>

                        <div class='col-sm-8'>
                            <input
                                    checked
                                    class='checkbox'
                                    id='seven_filter_only_enabled'
                                    name='seven_filter_only_enabled'
                                    type='checkbox'
                            />
                        </div>
                    </div>
                </fieldset>

                <div class='form-group row'>
                    <label for='seven_flash' class='col-sm-2 col-form-label'>
                        {{ sc_language_render($pathPlugin.'::lang.sms.flash.label') }}
                    </label>

                    <div class='col-sm-8'>
                        <input
                                class='checkbox'
                                id='seven_flash'
                                name='seven_flash'
                                type='checkbox'
                        />
                    </div>
                </div>

                <div class='form-group row'>
                    <label for='seven_performance_tracking' class='col-sm-2 col-form-label'>
                        {{ sc_language_render($pathPlugin.'::lang.sms.performance_tracking.label') }}
                    </label>

                    <div class='col-sm-8'>
                        <input
                                class='checkbox'
                                id='seven_performance_tracking'
                                name='seven_performance_tracking'
                                type='checkbox'
                        />
                    </div>
                </div>

                <div class='form-group row'>
                    <label for='seven_label' class='col-sm-2 col-form-label'>
                        {{ sc_language_render($pathPlugin.'::lang.sms.label.label') }}
                    </label>

                    <div class='col-sm-8'>
                        <input
                                class='form-control'
                                id='seven_label'
                                name='seven_label'
                                placeholder='{{ sc_language_render($pathPlugin.'::lang.sms.label.placeholder') }}'
                        />
                    </div>
                </div>

                <div class='form-group row'>
                    <label for='seven_foreign_id' class='col-sm-2 col-form-label'>
                        {{ sc_language_render($pathPlugin.'::lang.sms.foreign_id.label') }}
                    </label>

                    <div class='col-sm-8'>
                        <input
                                class='form-control'
                                id='seven_foreign_id'
                                name='seven_foreign_id'
                                placeholder='{{ sc_language_render($pathPlugin.'::lang.sms.foreign_id.placeholder') }}'
                        />
                    </div>
                </div>

                <div class='form-group row'>
                    <label for='seven_from' class='col-sm-2 col-form-label'>
                        {{ sc_language_render($pathPlugin.'::lang.sms.from.label') }}
                    </label>

                    <div class='col-sm-8'>
                        <input
                                class='form-control'
                                id='seven_from'
                                name='seven_from'
                                placeholder='{{ sc_language_render($pathPlugin.'::lang.sms.from.placeholder') }}'
                        />
                    </div>
                </div>

                <div class='form-group row'>
                    <label for='seven_text' class='col-sm-2 col-form-label'>
                        {{ sc_language_render($pathPlugin.'::lang.sms.text.label') }}
                    </label>

                    <div class='col-sm-8'>
                                <textarea
                                        class='form-control'
                                        id='seven_text'
                                        name='seven_text'
                                        placeholder='{{ sc_language_render($pathPlugin.'::lang.sms.text.placeholder') }}'
                                        required
                                ></textarea>
                    </div>
                </div>
            </div>

            <div class='card-footer'>
                <div class='btn-group float-left'>
                    <button class='btn btn-primary' type='submit'>
                        {{ sc_language_render($pathPlugin.'::lang.submit') }}
                        <i class='fas fa-envelope'></i>
                    </button>
                </div>

                <div class='btn-group float-right'>
                    <button class='btn btn-warning' type='reset'>
                        {{ sc_language_render('action.reset') }}
                        <i class='fas fa-undo'></i>
                    </button>
                </div>
            </div>

            @csrf
        </form>
    </div>
@endsection
