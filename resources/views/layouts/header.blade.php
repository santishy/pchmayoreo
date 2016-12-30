<!DOCTYPE html>
<html lang="en" style="height:100% !important">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{url('/css/app.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" media="screen" title="no title">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.3.0/css/material-fullpalette.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.3.0/css/ripples.min.css"-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.3.0/css/material-fullpalette.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.3.0/css/ripples.min.css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body style="min-height:100% !important;border:solid;height:100%">
    <div id="app">
        <header>
            <div class="container">
                <div class="row">

                    <div class="col-md-3">
                        <a href="">
                            <img src="{{url('configuraciones/image/logotipo.png')}}" class="img-responsive">
                        </a>

                    </div>
                    <div class="col-md-4 col-md-offset-1 top-space">
                        <p class="no-margin">Llamanos por cualquier duda sobre tu pedido:</p>
                        <a href="tel:+018000014726" class="earphone" style="font-weight:bold">
                            <span >Matriz Guadalajara 01 800 001 isco</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </div>
    @include('layouts.footer')