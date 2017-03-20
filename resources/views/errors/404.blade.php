<html>
    <head>
        <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

        <style>
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                color: #888;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Sorry - the page you're looking for can't be found.</div>
                <p>We'll take you to our homepage in a second.</p>
            </div>
        </div>
    </body>

    <script type="text/javascript">
        setTimeout(function(){
            // redirect to the homepage in N seconds (client request)
            window.location = '/';
        }, 3000);
    </script>
</html>
