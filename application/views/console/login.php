<!DOCTYPE html>
<html lang="kr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Administrator | PetPlace </title>

    <!-- Bootstrap core CSS -->

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">

    <link href="/assets/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="/assets/css/console.css" rel="stylesheet">
    <script src="/assets/libs/jquery.min.js"></script>
</head>

<body style="background:#F7F7F7;">
    
    <div class="">
        <div id="wrapper">
            <div id="login" class="animate form">
                <section class="login_content">
                    <form name="login_form" method="post" action="/console/login/login">
                        <h1>Administrator Login</h1>
                        <div>
                            <input type="text" name="console_id" class="form-control" placeholder="아이디를 입력하세요" required="required" />
                        </div>
                        <div>
                            <input type="password" name="console_pass" class="form-control" placeholder="비밀번호를 입력하세요" required="required" />
                        </div>
                        <div>
                            <input type="submit" class="btn btn-default submit" value="Log in"/>
                        </div>
                    </form>
                    <!-- form -->
                </section>
                <!-- content -->
            </div>
        </div>
    </div>
</body>
</html>