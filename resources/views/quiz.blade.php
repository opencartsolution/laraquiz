<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    </head>
    <body>
        <div class="container">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <h1 class="text-center">Welcome to Laravel</h1>
                <hr>
                <div class="row">
                    <div class="col-sm-12" id="step1">
                        <div class="form-group">
                            <label for="username" class="control-label">Yourname</label>
                            <input name="username" class="form-control" value="{{ $username }}" placeholder="Enter Yourname" id="input-username" /> 
                        </div>
                        <button type="button" class="btn btn-primary" id="button-step1">Next</button>
                    </div>
                    <div class="col-sm-12" id="step2">
                        <div class="form-group">
                            <label for="question1" class="control-label">{{ $label_q1 }}</label>
                            <div id="input-question1">
                                <input type="radio" name="question1" value="ll" @if ($question1 == 'll') checked @endif /> {{ html_entity_decode("<LL>") }}<br>
                                <input type="radio" name="question1" value="dd" @if ($question1 == 'dd') checked @endif /> {{ html_entity_decode("<DD>") }}<br>
                                <input type="radio" name="question1" value="dl" @if ($question1 == 'dl') checked @endif /> {{ html_entity_decode("<DL>") }}<br>
                                <input type="radio" name="question1" value="ds" @if ($question1 == 'ds') checked @endif /> {{ html_entity_decode("<DS>") }}<br>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="toogleBlock('3','')" id="button-step2-skip">Skip</button>
                        <button type="button" class="btn btn-primary" id="button-step2">Next</button>
                    </div>
                    <div class="col-sm-12" id="step3">
                        <div class="form-group">
                            <label for="question1" class="control-label">{{ html_entity_decode($label_q2) }}</label>
                            <div id="input-question2">
                                <input type="radio" name="question2" value="method" @if ($question2 == 'method') checked @endif /> Method<br>
                                <input type="radio" name="question2" value="action" @if ($question2 == 'action') checked @endif /> Action<br>
                                <input type="radio" name="question2" value="both" @if ($question2 == 'both') checked @endif /> Both (a)&(b)<br>
                                <input type="radio" name="question2" value="none" @if ($question2 == 'none') checked @endif /> None<br>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="toogleBlock('4','true')" id="button-step3-skip">Skip</button>
                        <button type="button" class="btn btn-primary" id="button-step3">Next</button>
                    </div>
                    <div class="col-sm-12" id="step4">
                        <h3>{{ $text_result }}</h3>
                        <ul class="list-unstyled">
                            <li class="list-item text-success">{{ $text_correct }}: <strong id="input-correct-ans"></strong></li>
                            <li class="list-item text-danger">{{ $text_wrong }}: <strong id="input-wrong-ans"></strong></li>
                            <li class="list-item text-warning">{{ $text_skip }}: <strong id="input-skip-ans"></strong></li>
                            <hr>
                            <button type="button" class="btn btn-primary" id="button-refresh">Start again</button>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        toogleBlock('1','');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#button-step1').on('click', function() {
            $.ajax({
                url: "{{ route('step1') }}",
                type: 'post',
                dataType: 'json',
                data: {'username' : $('input[name=\'username\']').val()},
                beforeSend: function() {
                    $('#button-step1').prop('disable', true);
                },
                complete: function() {
                    $('#button-step1').prop('disable', false);
                },
                success: function(json) {
                    $('.alert-dismissible').remove();

                    if (json['errors']) {
                        for (i in json['errors']) {
                            var element = $('#input-' + i.replace('_', '-'));
                            element.after('<div class="text-danger alert-dismissible">' + json['errors'][i] + '</div>');
                        }
                    }
                    if (json['success']) {
                        toogleBlock('2','');
                    }
                }
            });
        });

        $('#button-step2').on('click', function() {
            $.ajax({
                url: "{{ route('step2') }}",
                type: 'post',
                dataType: 'json',
                data: {'question1' : $('input[name=\'question1\']:checked').val()},
                beforeSend: function() {
                    $('#button-step2').prop('disable', true);
                },
                complete: function() {
                    $('#button-step2').prop('disable', false);
                },
                success: function(json) {
                    $('.alert-dismissible').remove();

                    if (json['errors']) {
                        for (i in json['errors']) {
                            var element = $('#input-' + i.replace('_', '-'));
                            element.after('<div class="text-danger alert-dismissible">' + json['errors'][i] + '</div>');
                        }
                    }
                    if (json['success']) {
                        toogleBlock('3','');
                    }
                }
            });
        });

        $('#button-step3').on('click', function() {
            $.ajax({
                url: "{{ route('step3') }}",
                type: 'post',
                dataType: 'json',
                data: {'question2' : $('input[name=\'question2\']:checked').val()},
                beforeSend: function() {
                    $('#button-step3').prop('disable', true);
                },
                complete: function() {
                    $('#button-step3').prop('disable', false);
                },
                success: function(json) {
                    $('.alert-dismissible').remove();

                    if (json['errors']) {
                        for (i in json['errors']) {
                            var element = $('#input-' + i.replace('_', '-'));
                            element.after('<div class="text-danger alert-dismissible">' + json['errors'][i] + '</div>');
                        }
                    }
                    if (json['success']) {
                        getResult();
                        toogleBlock('4','');
                    }
                }
            });
        });
        $('#button-refresh').on('click', function() {
            $.ajax({
                url: "{{ route('refresh_data') }}",
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    $('#button-refresh').prop('disable', true);
                },
                complete: function() {
                    $('#button-refresh').prop('disable', false);
                },
                success: function(json) {
                    location.reload();
                }
            });
        });
        function getResult(){
            $.ajax({
                url: "{{ route('getResult') }}",
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    $('#button-refresh').prop('disable', true);
                },
                complete: function() {
                    $('#button-refresh').prop('disable', false);
                },
                success: function(json) {
                    $('#input-correct-ans').text(json['correct_ans']);
                    $('#input-wrong-ans').text(json['wrong_ans']);
                    $('#input-skip-ans').text(json['skip_ans']);
                }
            });
        }

        function toogleBlock(id, event_trigger){
            $('div[id^=\'step\']').hide();
            $('#step'+id).show();
            if(event_trigger == 'true'){
                getResult();
            }
            
        }
        </script>
    </body>
</html>
