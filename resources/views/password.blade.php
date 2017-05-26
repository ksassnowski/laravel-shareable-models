<!doctype html>
<html>
<head>
    <title>Password Protected</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            height: 100%;
            font-family: "Open Sans", sans-serif;
            font-weight: 300;
            font-size: 14px;
        }

        body {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        p {
            margin: 0 0 15px 0;
        }

        .icon {
            width: 60%;
            fill: #fff;
        }

        .box {
            width: 350px;
            border-radius: 2px;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14),
            0 3px 1px -2px rgba(0, 0, 0, .2),
            0 1px 5px 0 rgba(0, 0, 0, .12);
            background-color: #fff;
        }

        .box__header {
            padding: 16px;
            background: #006664;
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;
            display: flex;
            justify-content: center;
        }

        .box__body {
            padding: 16px;
        }

        .box__footer {
            padding: 12px 16px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .password__help {
            color: #7a7a7a;
        }

        .password__label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .password__input {
            width: 100%;
            height: 38px;
            padding: 0 10px;
            border: 1px solid #006664;
            border-radius: 2px;
            font-size: 14px;
        }

        .password__button {
            border: none;
            outline: none;
            background-color: #006664;
            color: #fff;
            padding: 0 16px;
            font-size: 15px;
            text-transform: uppercase;
            border-radius: 2px;
            line-height: 36px;
            cursor: pointer;
            transition: background-color 0.3s linear;
            will-change: background-color;
        }

        .password__button:hover {
            background-color: rgba(0, 127, 125, 0.8);
        }
    </style>
</head>

<body>
<div class="box">
    <form action="{{ url(config('shareable-model.redirect_routes.password_protected'), $link->hash) }}" method="POST">
        {{ csrf_field() }}

        <div class="box__header">
            <svg class="icon" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                <path d="M640 768h512v-192q0-106-75-181t-181-75-181 75-75 181v192zm832 96v576q0 40-28 68t-68 28h-960q-40 0-68-28t-28-68v-576q0-40 28-68t68-28h32v-192q0-184 132-316t316-132 316 132 132 316v192h32q40 0 68 28t28 68z"/>
            </svg>
        </div>

        <div class="box__body">
            <p class="password__help">The resource you are trying to access is password protected. Please enter the password.</p>

            <div class="password__input-group">
                <label class="password__label" for="password">Password:</label>
                <input type="password" class="password__input" name="password" id="password" placeholder="super-secret-password" required>
            </div>
        </div>

        <div class="box__footer">
            <div class="password__input-group">
                <button type="submit" class="password__button">Submit</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
