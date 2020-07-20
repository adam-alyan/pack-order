<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

        
            <div class="content">                
                @if($packSizes)
                    <div class="title m-b-md">
                        Order {{ $productName }}s
                    </div>
                    <div class="links">
                    <a href='{!! url('/'); !!}'>Home</a>
                </div>
                    <h3>Pack Sizes</h3>
                    <div>
                        <p>
                            @foreach($packSizes as $packSize)
                                <span>{{ $packSize }} {{ $productName }}s Pack</span><br/>
                            @endforeach 
                        </p>
                    </div>                    

                    @unless($noItems)
                        <div>
                            <h3>Order Form</h3>
                            <form method="post" action='{!! url('/order'); !!}/{{$productId}}'>
                                {{csrf_field()}}

                                <label for="noItems">Number of Items</label><br/>
                                <input id="noItems" type="number" name="noItems" value="" required
                        autofocus placeholder="Enter number of items you would like to order">
                                <button class="btn btn-primary btn-block" type="submit">Order</button>
                            </form>
                            @isset($error)
                                <Span style="color:red;">{{ $error }}</span>
                            @endisset
                        <div>                
                    @endunless
                    @isset($packsToSend)
                        <div>
                            <h3>Thank you for your order.</h3> 
                            <p style="color:green;">
                            <span>You've ordered {{ $noItems }} items, You'll receive the following:</span><br/>
                                @foreach($packsToSend as $packSize => $noPacks)
                                    <span>{{ $noPacks }} x {{ $packSize }} Widgets Pack </span><br/>
                                @endforeach  
                            </p>
                        </div>
                    @endisset
                @endif


            </div>
        </div>
    </body>
</html>
