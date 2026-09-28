<?php

/*
|--------------------------------------------------------------------------
| Fast Foto Colour Lab
| Security Configuration
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {

    $secure_cookie = (
        isset($_SERVER["HTTPS"]) &&
        $_SERVER["HTTPS"] !== "off"
    );

    session_set_cookie_params([
        "lifetime" => 0,
        "path" => "/",
        "domain" => "",
        "secure" => $secure_cookie,
        "httponly" => true,
        "samesite" => "Lax"
    ]);

    session_start();
}


/*
|--------------------------------------------------------------------------
| SECURITY HEADERS
|--------------------------------------------------------------------------
*/

function apply_security_headers()
{
    if (!headers_sent()) {

        header("X-Content-Type-Options: nosniff");

        header("X-Frame-Options: SAMEORIGIN");

        header(
            "Referrer-Policy: strict-origin-when-cross-origin"
        );

        header(
            "Permissions-Policy: camera=(), microphone=(), geolocation=()"
        );
    }
}


/*
|--------------------------------------------------------------------------
| HTML ESCAPE
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        "UTF-8"
    );
}


/*
|--------------------------------------------------------------------------
| CSRF TOKEN
|--------------------------------------------------------------------------
*/

function csrf_token()
{
    if (empty($_SESSION["csrf_token"])) {

        $_SESSION["csrf_token"] =
            bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}


/*
|--------------------------------------------------------------------------
| VERIFY CSRF TOKEN
|--------------------------------------------------------------------------
*/

function verify_csrf_token($token)
{
    if (
        empty($token) ||
        empty($_SESSION["csrf_token"])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION["csrf_token"],
        (string)$token
    );
}


/*
|--------------------------------------------------------------------------
| REQUIRE CSRF TOKEN
|--------------------------------------------------------------------------
*/

function require_csrf_token($token)
{
    if (!verify_csrf_token($token)) {

        http_response_code(403);

        die("
        <!DOCTYPE html>

        <html lang='en'>

        <head>

            <meta charset='UTF-8'>

            <meta
                name='viewport'
                content='width=device-width, initial-scale=1.0'
            >

            <title>Security Error</title>

            <style>

                * {
                    box-sizing: border-box;
                }

                body {

                    margin: 0;

                    min-height: 100vh;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    padding: 25px;

                    font-family:
                        Arial,
                        Helvetica,
                        sans-serif;

                    color: #ffffff;

                    background:
                        radial-gradient(
                            circle at top,
                            #312e81,
                            #080814 55%
                        );
                }

                .security-card {

                    width: 100%;

                    max-width: 520px;

                    padding: 45px 35px;

                    text-align: center;

                    background:
                        rgba(255,255,255,0.08);

                    border:
                        1px solid
                        rgba(255,255,255,0.15);

                    border-radius: 25px;

                    backdrop-filter: blur(20px);

                    -webkit-backdrop-filter: blur(20px);

                    box-shadow:
                        0 30px 80px
                        rgba(0,0,0,0.45);
                }

                .icon {

                    width: 80px;

                    height: 80px;

                    margin: 0 auto 20px;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    border-radius: 50%;

                    background:
                        linear-gradient(
                            135deg,
                            #ef4444,
                            #7f1d1d
                        );

                    font-size: 38px;

                    box-shadow:
                        0 15px 35px
                        rgba(239,68,68,0.35);
                }

                h1 {
                    margin: 0 0 12px;
                }

                p {

                    color: #cbd5e1;

                    line-height: 1.7;
                }

                a {

                    display: inline-block;

                    margin-top: 20px;

                    padding: 13px 25px;

                    border-radius: 14px;

                    color: white;

                    text-decoration: none;

                    font-weight: 700;

                    background:
                        linear-gradient(
                            135deg,
                            #6366f1,
                            #8b5cf6,
                            #06b6d4
                        );

                    box-shadow:
                        0 7px 0 #312e81,
                        0 15px 30px
                        rgba(99,102,241,0.35);
                }

            </style>

        </head>

        <body>

            <div class='security-card'>

                <div class='icon'>
                    🔐
                </div>

                <h1>
                    403 - Security Check Failed
                </h1>

                <p>
                    Your request could not be verified.
                    Please return to the previous page
                    and try again.
                </p>

                <a href='javascript:history.back()'>
                    ← Go Back
                </a>

            </div>

        </body>

        </html>
        ");
    }
}


/*
|--------------------------------------------------------------------------
| REQUIRE LOGIN
|--------------------------------------------------------------------------
*/

function require_login()
{
    if (
        !isset($_SESSION["user_id"]) ||
        !is_numeric($_SESSION["user_id"])
    ) {

        header(
            "Location: /photo-studio/login.php"
        );

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| REQUIRE ADMIN
|--------------------------------------------------------------------------
*/

function require_admin()
{
    /*
    |--------------------------------------------------------------------------
    | Get logged-in user ID
    |--------------------------------------------------------------------------
    */

    $user_id = $_SESSION["user_id"] ?? null;


    /*
    |--------------------------------------------------------------------------
    | Get role
    |--------------------------------------------------------------------------
    |
    | Current login.php uses:
    | $_SESSION["role"]
    |
    | Older project files may use:
    | $_SESSION["user_role"]
    |
    */

    $role = $_SESSION["role"]
        ?? $_SESSION["user_role"]
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | User is not logged in
    |--------------------------------------------------------------------------
    */

    if (
        empty($user_id) ||
        !is_numeric($user_id)
    ) {

        header(
            "Location: /photo-studio/login.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | User is logged in but is not an admin
    |--------------------------------------------------------------------------
    */

    if ($role !== "admin") {

        header(
            "Location: /photo-studio/dashboard.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Synchronize old and new session names
    |--------------------------------------------------------------------------
    */

    $_SESSION["role"] = "admin";

    $_SESSION["user_role"] = "admin";
}


/*
|--------------------------------------------------------------------------
| REQUIRE POST
|--------------------------------------------------------------------------
*/

function require_post()
{
    if (
        $_SERVER["REQUEST_METHOD"] !== "POST"
    ) {

        http_response_code(405);

        header("Allow: POST");

        die("Method Not Allowed.");
    }
}


/*
|--------------------------------------------------------------------------
| VALIDATE POSITIVE INTEGER ID
|--------------------------------------------------------------------------
*/

function valid_id($value)
{
    return (
        filter_var(
            $value,
            FILTER_VALIDATE_INT
        ) !== false
        &&
        (int)$value > 0
    );
}
