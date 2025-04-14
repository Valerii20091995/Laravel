<div class="wrapper">
    <form class="form-signin" action="./login" method="POST">
        @csrf
        <h2 class="form-signin-heading">Please login</h2>
        <label for="email" class="sr-only">Email Address</label>
        <input type="text" class="form-control" name="email" id="email" placeholder="Email Address" required autofocus="" />
        @error('email')
        <label for="email"><b>{{ $message }}</b></label>
        @enderror
        <label for="password" class="sr-only">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required/>
        @error('password')
        <label for="password"><b>{{ $message }}</b></label>
        @enderror
        <label class="checkbox">
            <input type="checkbox" value="remember-me" id="rememberMe" name="rememberMe"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <a href="/signUp" class="navbar-link">Registration</a>
    </form>
</div>
<style>
    @import "bourbon";

    body {
        background: #eee !important;
    }

    .wrapper {
        margin-top: 80px;
        margin-bottom: 80px;
    }

    .form-signin {
        max-width: 380px;
        padding: 15px 35px 45px;
        margin: 0 auto;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,0.1);

        .form-signin-heading,
        .checkbox {
            margin-bottom: 30px;
        }

        .checkbox {
            font-weight: normal;
        }

        .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
        .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
            box-sizing: border-box;

    &:focus {
        z-index: 2;
    }
}

            &:focus {
                z-index: 2;
            }
        }

        input[type="text"] {
            margin-bottom: -1px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        input[type="password"] {
            margin-bottom: 20px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    }
</style>
