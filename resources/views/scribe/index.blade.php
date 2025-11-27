<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://127.0.0.1:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.5.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.5.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-login">
                                <a href="#endpoints-POSTapi-login">POST api/login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-register">
                                <a href="#endpoints-POSTapi-register">POST api/register</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-forgot-password">
                                <a href="#endpoints-POSTapi-forgot-password">Send OTP to user's email</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-verify-otp">
                                <a href="#endpoints-POSTapi-verify-otp">Verify OTP</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-reset-password">
                                <a href="#endpoints-POSTapi-reset-password">Reset password with OTP</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-forgot-password">
                                <a href="#endpoints-GETapi-forgot-password">GET api/forgot-password</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-logout">
                                <a href="#endpoints-POSTapi-logout">POST api/logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-me">
                                <a href="#endpoints-GETapi-me">GET api/me</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-profile">
                                <a href="#endpoints-GETapi-profile">Get the authenticated user's profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-profile">
                                <a href="#endpoints-POSTapi-profile">Update the authenticated user's profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-categories">
                                <a href="#endpoints-GETapi-categories">Display a listing of all active categories.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-categories--category_slug-">
                                <a href="#endpoints-GETapi-categories--category_slug-">Display the specified category.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-categories--category_slug--courses">
                                <a href="#endpoints-GETapi-categories--category_slug--courses">Get courses by category.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-user">
                                <a href="#endpoints-GETapi-user">GET api/user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-courses">
                                <a href="#endpoints-GETapi-courses">Display a listing of courses with filters and pagination.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-courses--slug-">
                                <a href="#endpoints-GETapi-courses--slug-">GET api/courses/{slug}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-instructors">
                                <a href="#endpoints-GETapi-instructors">GET api/instructors</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-levels">
                                <a href="#endpoints-GETapi-levels">GET api/levels</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-plans">
                                <a href="#endpoints-GETapi-plans">GET api/plans</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-plans--slug-">
                                <a href="#endpoints-GETapi-plans--slug-">GET api/plans/{slug}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-subscriptions-popular-plan">
                                <a href="#endpoints-GETapi-subscriptions-popular-plan">GET api/subscriptions/popular-plan</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-courses--course_id--reviews">
                                <a href="#endpoints-GETapi-courses--course_id--reviews">Get all reviews for a specific course.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-verify-certificate--certificateNumber-">
                                <a href="#endpoints-GETapi-verify-certificate--certificateNumber-">Verify certificate by certificate number (public route)</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-enrollments">
                                <a href="#endpoints-GETapi-enrollments">GET api/enrollments</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-enrollments">
                                <a href="#endpoints-POSTapi-enrollments">POST api/enrollments</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-dashboard">
                                <a href="#endpoints-GETapi-dashboard">GET api/dashboard</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-courses--courseId--progress">
                                <a href="#endpoints-GETapi-courses--courseId--progress">Get course progress for authenticated user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-courses--courseId--videos--videoId--complete">
                                <a href="#endpoints-POSTapi-courses--courseId--videos--videoId--complete">Mark video as completed</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-courses--courseId--time-spent">
                                <a href="#endpoints-POSTapi-courses--courseId--time-spent">Update time spent on course</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-dashboard-progress">
                                <a href="#endpoints-GETapi-dashboard-progress">Get user's dashboard progress data</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-certificates">
                                <a href="#endpoints-GETapi-certificates">Get all certificates for the authenticated user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-courses--courseId--certificate-generate">
                                <a href="#endpoints-POSTapi-courses--courseId--certificate-generate">Generate and download certificate for a course</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-courses--courseId--certificate-preview">
                                <a href="#endpoints-GETapi-courses--courseId--certificate-preview">Preview certificate inline</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-courses--courseId--certificate-check-eligibility">
                                <a href="#endpoints-GETapi-courses--courseId--certificate-check-eligibility">Check if user is eligible for a certificate</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-certificates--certificateId--download">
                                <a href="#endpoints-GETapi-certificates--certificateId--download">Download certificate by ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-test--courseId-">
                                <a href="#endpoints-GETapi-test--courseId-">GET api/test/{courseId}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-test-submit">
                                <a href="#endpoints-POSTapi-test-submit">POST api/test/submit</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-test-status--courseId-">
                                <a href="#endpoints-GETapi-test-status--courseId-">GET api/test/status/{courseId}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-certificate-download--courseId-">
                                <a href="#endpoints-GETapi-certificate-download--courseId-">Download certificate by course ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-courses--course_id--reviews">
                                <a href="#endpoints-POSTapi-courses--course_id--reviews">Store a new review.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PUTapi-reviews--review_id-">
                                <a href="#endpoints-PUTapi-reviews--review_id-">Update the specified review.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-reviews--review_id-">
                                <a href="#endpoints-DELETEapi-reviews--review_id-">Remove the specified review.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-courses--courseId--enrollment-status">
                                <a href="#endpoints-GETapi-courses--courseId--enrollment-status">Get course enrollment status for a user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-create-order">
                                <a href="#endpoints-GETapi-create-order">GET api/create-order</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-enrollments-check">
                                <a href="#endpoints-POSTapi-enrollments-check">POST api/enrollments/check</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-subscriptions-subscribe">
                                <a href="#endpoints-POSTapi-subscriptions-subscribe">POST api/subscriptions/subscribe</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-subscriptions-cancel">
                                <a href="#endpoints-POSTapi-subscriptions-cancel">POST api/subscriptions/cancel</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-subscriptions-status">
                                <a href="#endpoints-GETapi-subscriptions-status">GET api/subscriptions/status</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-subscriptions-courses">
                                <a href="#endpoints-GETapi-subscriptions-courses">GET api/subscriptions/courses</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-subscriptions">
                                <a href="#endpoints-GETapi-subscriptions">GET api/subscriptions</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-wishlist">
                                <a href="#endpoints-GETapi-wishlist">GET api/wishlist</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-wishlist">
                                <a href="#endpoints-POSTapi-wishlist">POST api/wishlist</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-wishlist--course-">
                                <a href="#endpoints-DELETEapi-wishlist--course-">DELETE api/wishlist/{course}</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: November 14, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://127.0.0.1:8000</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-POSTapi-login">POST api/login</h2>

<p>
</p>



<span id="example-requests-POSTapi-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"qkunze@example.com\",
    \"password\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "qkunze@example.com",
    "password": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-login">
</span>
<span id="execution-results-POSTapi-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-login" data-method="POST"
      data-path="api/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-login"
                    onclick="tryItOut('POSTapi-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-login"
                    onclick="cancelTryOut('POSTapi-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-login"
               value="qkunze@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. Example: <code>qkunze@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-login"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-register">POST api/register</h2>

<p>
</p>



<span id="example-requests-POSTapi-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"vmqeopfuudtdsufvyvddq\",
    \"email\": \"kunde.eloisa@example.com\",
    \"password\": \"4[*UyPJ\\\"}6\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "vmqeopfuudtdsufvyvddq",
    "email": "kunde.eloisa@example.com",
    "password": "4[*UyPJ\"}6"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-register">
</span>
<span id="execution-results-POSTapi-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-register" data-method="POST"
      data-path="api/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-register"
                    onclick="tryItOut('POSTapi-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-register"
                    onclick="cancelTryOut('POSTapi-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-register"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-register"
               value="kunde.eloisa@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. Example: <code>kunde.eloisa@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-register"
               value="4[*UyPJ"}6"
               data-component="body">
    <br>
<p>Must be at least 6 characters. Example: <code>4[*UyPJ"}6</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-forgot-password">Send OTP to user&#039;s email</h2>

<p>
</p>



<span id="example-requests-POSTapi-forgot-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/forgot-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"qkunze@example.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/forgot-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "qkunze@example.com"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-forgot-password">
</span>
<span id="execution-results-POSTapi-forgot-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-forgot-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-forgot-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-forgot-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-forgot-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-forgot-password" data-method="POST"
      data-path="api/forgot-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-forgot-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-forgot-password"
                    onclick="tryItOut('POSTapi-forgot-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-forgot-password"
                    onclick="cancelTryOut('POSTapi-forgot-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-forgot-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/forgot-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-forgot-password"
               value="qkunze@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. The <code>email</code> of an existing record in the users table. Example: <code>qkunze@example.com</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-verify-otp">Verify OTP</h2>

<p>
</p>



<span id="example-requests-POSTapi-verify-otp">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/verify-otp" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"qkunze@example.com\",
    \"otp\": \"810798\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/verify-otp"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "qkunze@example.com",
    "otp": "810798"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-verify-otp">
</span>
<span id="execution-results-POSTapi-verify-otp" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-verify-otp"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-verify-otp"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-verify-otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-verify-otp">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-verify-otp" data-method="POST"
      data-path="api/verify-otp"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-verify-otp', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-verify-otp"
                    onclick="tryItOut('POSTapi-verify-otp');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-verify-otp"
                    onclick="cancelTryOut('POSTapi-verify-otp');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-verify-otp"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/verify-otp</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-verify-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-verify-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-verify-otp"
               value="qkunze@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. The <code>email</code> of an existing record in the users table. Example: <code>qkunze@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="otp"                data-endpoint="POSTapi-verify-otp"
               value="810798"
               data-component="body">
    <br>
<p>Must be 6 digits. Example: <code>810798</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-reset-password">Reset password with OTP</h2>

<p>
</p>



<span id="example-requests-POSTapi-reset-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/reset-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"qkunze@example.com\",
    \"otp\": \"810798\",
    \"password\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/reset-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "qkunze@example.com",
    "otp": "810798",
    "password": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-reset-password">
</span>
<span id="execution-results-POSTapi-reset-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-reset-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-reset-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-reset-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-reset-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-reset-password" data-method="POST"
      data-path="api/reset-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-reset-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-reset-password"
                    onclick="tryItOut('POSTapi-reset-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-reset-password"
                    onclick="cancelTryOut('POSTapi-reset-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-reset-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/reset-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-reset-password"
               value="qkunze@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. The <code>email</code> of an existing record in the users table. Example: <code>qkunze@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="otp"                data-endpoint="POSTapi-reset-password"
               value="810798"
               data-component="body">
    <br>
<p>Must be 6 digits. Example: <code>810798</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-reset-password"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-forgot-password">GET api/forgot-password</h2>

<p>
</p>



<span id="example-requests-GETapi-forgot-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/forgot-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/forgot-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-forgot-password">
            <blockquote>
            <p>Example response (302):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
location: http://127.0.0.1:8000/forgot-password
content-type: text/html; charset=utf-8
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&lt;!DOCTYPE html&gt;
&lt;html&gt;
    &lt;head&gt;
        &lt;meta charset=&quot;UTF-8&quot; /&gt;
        &lt;meta http-equiv=&quot;refresh&quot; content=&quot;0;url=&#039;http://127.0.0.1:8000/forgot-password&#039;&quot; /&gt;

        &lt;title&gt;Redirecting to http://127.0.0.1:8000/forgot-password&lt;/title&gt;
    &lt;/head&gt;
    &lt;body&gt;
        Redirecting to &lt;a href=&quot;http://127.0.0.1:8000/forgot-password&quot;&gt;http://127.0.0.1:8000/forgot-password&lt;/a&gt;.
    &lt;/body&gt;
&lt;/html&gt;</code>
 </pre>
    </span>
<span id="execution-results-GETapi-forgot-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-forgot-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-forgot-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-forgot-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-forgot-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-forgot-password" data-method="GET"
      data-path="api/forgot-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-forgot-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-forgot-password"
                    onclick="tryItOut('GETapi-forgot-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-forgot-password"
                    onclick="cancelTryOut('GETapi-forgot-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-forgot-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/forgot-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-logout">POST api/logout</h2>

<p>
</p>



<span id="example-requests-POSTapi-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-logout">
</span>
<span id="execution-results-POSTapi-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-logout" data-method="POST"
      data-path="api/logout"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-logout"
                    onclick="tryItOut('POSTapi-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-logout"
                    onclick="cancelTryOut('POSTapi-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-me">GET api/me</h2>

<p>
</p>



<span id="example-requests-GETapi-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/me" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/me"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-me">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-me" data-method="GET"
      data-path="api/me"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-me"
                    onclick="tryItOut('GETapi-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-me"
                    onclick="cancelTryOut('GETapi-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-profile">Get the authenticated user&#039;s profile</h2>

<p>
</p>



<span id="example-requests-GETapi-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/profile" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/profile"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-profile">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-profile" data-method="GET"
      data-path="api/profile"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-profile"
                    onclick="tryItOut('GETapi-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-profile"
                    onclick="cancelTryOut('GETapi-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-profile">Update the authenticated user&#039;s profile</h2>

<p>
</p>



<span id="example-requests-POSTapi-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/profile" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=vmqeopfuudtdsufvyvddq"\
    --form "email=kunde.eloisa@example.com"\
    --form "phone=hfqcoynlazghdtqtq"\
    --form "bio=xbajwbpilpmufinllwloa"\
    --form "date_of_birth=2020-03-11"\
    --form "address=mqeopfuudtdsufvyvddqa"\
    --form "profile_picture=@C:\Users\prajin\AppData\Local\Temp\phpC156.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/profile"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'vmqeopfuudtdsufvyvddq');
body.append('email', 'kunde.eloisa@example.com');
body.append('phone', 'hfqcoynlazghdtqtq');
body.append('bio', 'xbajwbpilpmufinllwloa');
body.append('date_of_birth', '2020-03-11');
body.append('address', 'mqeopfuudtdsufvyvddqa');
body.append('profile_picture', document.querySelector('input[name="profile_picture"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-profile">
</span>
<span id="execution-results-POSTapi-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-profile" data-method="POST"
      data-path="api/profile"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-profile"
                    onclick="tryItOut('POSTapi-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-profile"
                    onclick="cancelTryOut('POSTapi-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-profile"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-profile"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-profile"
               value="kunde.eloisa@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. Must not be greater than 255 characters. Example: <code>kunde.eloisa@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-profile"
               value="hfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must not be greater than 20 characters. Example: <code>hfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>bio</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="bio"                data-endpoint="POSTapi-profile"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 1000 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date_of_birth</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="date_of_birth"                data-endpoint="POSTapi-profile"
               value="2020-03-11"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a date before <code>today</code>. Example: <code>2020-03-11</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-profile"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>profile_picture</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="profile_picture"                data-endpoint="POSTapi-profile"
               value=""
               data-component="body">
    <br>
<p>Must be an image. Must not be greater than 3048 kilobytes. Example: <code>C:\Users\prajin\AppData\Local\Temp\phpC156.tmp</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-categories">Display a listing of all active categories.</h2>

<p>
</p>



<span id="example-requests-GETapi-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/categories" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/categories"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-categories">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Qui&quot;,
            &quot;slug&quot;: &quot;eum&quot;,
            &quot;description&quot;: &quot;Quis qui praesentium recusandae.&quot;,
            &quot;image&quot;: &quot;categories/1.png&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Est&quot;,
            &quot;slug&quot;: &quot;quia&quot;,
            &quot;description&quot;: &quot;Reiciendis accusantium repudiandae quidem quo ad voluptas.&quot;,
            &quot;image&quot;: &quot;categories/2.png&quot;
        },
        {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Corporis&quot;,
            &quot;slug&quot;: &quot;ab&quot;,
            &quot;description&quot;: &quot;Harum et minima veniam labore est.&quot;,
            &quot;image&quot;: &quot;categories/3.png&quot;
        },
        {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;Cumque&quot;,
            &quot;slug&quot;: &quot;dolorem&quot;,
            &quot;description&quot;: &quot;Eveniet et est ea expedita sint sit est.&quot;,
            &quot;image&quot;: &quot;categories/4.png&quot;
        },
        {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;Blanditiis&quot;,
            &quot;slug&quot;: &quot;optio&quot;,
            &quot;description&quot;: &quot;Adipisci aut possimus itaque velit in maiores.&quot;,
            &quot;image&quot;: &quot;categories/5.png&quot;
        },
        {
            &quot;id&quot;: 6,
            &quot;name&quot;: &quot;Nihil&quot;,
            &quot;slug&quot;: &quot;earum&quot;,
            &quot;description&quot;: &quot;Rerum ut sed qui et eaque possimus et.&quot;,
            &quot;image&quot;: &quot;categories/6.png&quot;
        },
        {
            &quot;id&quot;: 7,
            &quot;name&quot;: &quot;Distinctio&quot;,
            &quot;slug&quot;: &quot;atque&quot;,
            &quot;description&quot;: &quot;Maxime dolore vel quam quibusdam aut accusantium.&quot;,
            &quot;image&quot;: &quot;categories/7.png&quot;
        },
        {
            &quot;id&quot;: 8,
            &quot;name&quot;: &quot;Voluptates&quot;,
            &quot;slug&quot;: &quot;similique&quot;,
            &quot;description&quot;: &quot;Et quasi harum pariatur eveniet ullam dignissimos tempora.&quot;,
            &quot;image&quot;: &quot;categories/8.png&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-categories" data-method="GET"
      data-path="api/categories"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-categories"
                    onclick="tryItOut('GETapi-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-categories"
                    onclick="cancelTryOut('GETapi-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-categories--category_slug-">Display the specified category.</h2>

<p>
</p>



<span id="example-requests-GETapi-categories--category_slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/categories/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/categories/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-categories--category_slug-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Category].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-categories--category_slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-categories--category_slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-categories--category_slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-categories--category_slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-categories--category_slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-categories--category_slug-" data-method="GET"
      data-path="api/categories/{category_slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-categories--category_slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-categories--category_slug-"
                    onclick="tryItOut('GETapi-categories--category_slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-categories--category_slug-"
                    onclick="cancelTryOut('GETapi-categories--category_slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-categories--category_slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/categories/{category_slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-categories--category_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-categories--category_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_slug</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_slug"                data-endpoint="GETapi-categories--category_slug-"
               value="1"
               data-component="url">
    <br>
<p>The slug of the category. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-categories--category_slug--courses">Get courses by category.</h2>

<p>
</p>



<span id="example-requests-GETapi-categories--category_slug--courses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/categories/1/courses" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/categories/1/courses"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-categories--category_slug--courses">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Category].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-categories--category_slug--courses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-categories--category_slug--courses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-categories--category_slug--courses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-categories--category_slug--courses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-categories--category_slug--courses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-categories--category_slug--courses" data-method="GET"
      data-path="api/categories/{category_slug}/courses"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-categories--category_slug--courses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-categories--category_slug--courses"
                    onclick="tryItOut('GETapi-categories--category_slug--courses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-categories--category_slug--courses"
                    onclick="cancelTryOut('GETapi-categories--category_slug--courses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-categories--category_slug--courses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/categories/{category_slug}/courses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-categories--category_slug--courses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-categories--category_slug--courses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_slug</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_slug"                data-endpoint="GETapi-categories--category_slug--courses"
               value="1"
               data-component="url">
    <br>
<p>The slug of the category. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-user">GET api/user</h2>

<p>
</p>



<span id="example-requests-GETapi-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/user" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user" data-method="GET"
      data-path="api/user"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user"
                    onclick="tryItOut('GETapi-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user"
                    onclick="cancelTryOut('GETapi-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-courses">Display a listing of courses with filters and pagination.</h2>

<p>
</p>



<span id="example-requests-GETapi-courses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/courses" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-courses">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;title&quot;: &quot;Course 1: Consequuntur&quot;,
            &quot;slug&quot;: &quot;course-1-VgXES&quot;,
            &quot;description&quot;: &quot;Eos temporibus animi esse nihil quaerat doloribus ut. Nihil laborum animi quo consequatur pariatur harum. Quasi qui voluptas vero incidunt dolorem. Doloribus sit occaecati ipsa pariatur dolor minus qui.&quot;,
            &quot;price&quot;: &quot;1620.18&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/2.png&quot;,
            &quot;level&quot;: &quot;intermediate&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 6,
                &quot;name&quot;: &quot;Instructor 1&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Qui&quot;,
                    &quot;slug&quot;: &quot;eum&quot;
                }
            ],
            &quot;enrollments_count&quot;: 0,
            &quot;rating&quot;: 2.9,
            &quot;reviews_count&quot;: 10,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: true,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Excepturi laborum voluptas aliquid exercitationem rerum quia error.&quot;,
                    &quot;duration_in_seconds&quot;: 282,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/1.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/1.jpg&quot;
                },
                {
                    &quot;id&quot;: 2,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Quidem numquam commodi quidem cum sint quaerat consequatur.&quot;,
                    &quot;duration_in_seconds&quot;: 771,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/2.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/2.jpg&quot;
                },
                {
                    &quot;id&quot;: 3,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Praesentium molestias enim corporis ut quod aspernatur.&quot;,
                    &quot;duration_in_seconds&quot;: 591,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/3.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/3.jpg&quot;
                },
                {
                    &quot;id&quot;: 4,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Magni esse et consequatur pariatur aperiam nostrum in.&quot;,
                    &quot;duration_in_seconds&quot;: 890,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/4.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/4.jpg&quot;
                },
                {
                    &quot;id&quot;: 5,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Sint delectus molestiae et eaque perferendis.&quot;,
                    &quot;duration_in_seconds&quot;: 821,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/5.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/5.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 2,
            &quot;title&quot;: &quot;Course 2: Et&quot;,
            &quot;slug&quot;: &quot;course-2-foZOr&quot;,
            &quot;description&quot;: &quot;Aliquid non quae nihil sint quaerat. Ut nisi ut consequuntur nihil minus.&quot;,
            &quot;price&quot;: &quot;926.99&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/3.png&quot;,
            &quot;level&quot;: &quot;beginner&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 10,
                &quot;name&quot;: &quot;Instructor 5&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 6,
                    &quot;name&quot;: &quot;Nihil&quot;,
                    &quot;slug&quot;: &quot;earum&quot;
                }
            ],
            &quot;enrollments_count&quot;: 1,
            &quot;rating&quot;: 3.1,
            &quot;reviews_count&quot;: 10,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: false,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 6,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Occaecati quia cupiditate necessitatibus iusto dolor.&quot;,
                    &quot;duration_in_seconds&quot;: 218,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/6.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/6.jpg&quot;
                },
                {
                    &quot;id&quot;: 7,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Odio mollitia maiores voluptas omnis.&quot;,
                    &quot;duration_in_seconds&quot;: 399,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/7.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/7.jpg&quot;
                },
                {
                    &quot;id&quot;: 8,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Aut sequi aut qui voluptas beatae magnam.&quot;,
                    &quot;duration_in_seconds&quot;: 298,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/8.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/8.jpg&quot;
                },
                {
                    &quot;id&quot;: 9,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Recusandae quis quod harum.&quot;,
                    &quot;duration_in_seconds&quot;: 337,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/9.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/9.jpg&quot;
                },
                {
                    &quot;id&quot;: 10,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Quidem voluptate quia est sint non.&quot;,
                    &quot;duration_in_seconds&quot;: 177,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/10.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/10.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 3,
            &quot;title&quot;: &quot;Course 3: Excepturi&quot;,
            &quot;slug&quot;: &quot;course-3-0foOS&quot;,
            &quot;description&quot;: &quot;Et aut saepe vitae sed. Voluptas qui aperiam tempore enim aut enim voluptas. Aut eius aut placeat quasi dolor laborum.&quot;,
            &quot;price&quot;: &quot;718.33&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/4.png&quot;,
            &quot;level&quot;: &quot;intermediate&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 7,
                &quot;name&quot;: &quot;Instructor 2&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 6,
                    &quot;name&quot;: &quot;Nihil&quot;,
                    &quot;slug&quot;: &quot;earum&quot;
                }
            ],
            &quot;enrollments_count&quot;: 1,
            &quot;rating&quot;: 2.1111,
            &quot;reviews_count&quot;: 9,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: true,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 11,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Rerum quas ratione qui explicabo.&quot;,
                    &quot;duration_in_seconds&quot;: 710,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/11.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/11.jpg&quot;
                },
                {
                    &quot;id&quot;: 12,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Excepturi iure corporis minima suscipit.&quot;,
                    &quot;duration_in_seconds&quot;: 160,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/12.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/12.jpg&quot;
                },
                {
                    &quot;id&quot;: 13,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Vitae quam inventore aliquid optio ipsum et necessitatibus.&quot;,
                    &quot;duration_in_seconds&quot;: 488,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/13.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/13.jpg&quot;
                },
                {
                    &quot;id&quot;: 14,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Totam sunt aut dicta ut accusantium qui.&quot;,
                    &quot;duration_in_seconds&quot;: 403,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/14.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/14.jpg&quot;
                },
                {
                    &quot;id&quot;: 15,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Maxime impedit reiciendis eligendi commodi.&quot;,
                    &quot;duration_in_seconds&quot;: 544,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/15.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/15.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 4,
            &quot;title&quot;: &quot;Course 4: Enim&quot;,
            &quot;slug&quot;: &quot;course-4-0ib9i&quot;,
            &quot;description&quot;: &quot;Accusantium et quos et facilis velit. Delectus aliquid distinctio molestiae quia non eligendi. Et reprehenderit optio distinctio perferendis. Officiis eum ut fuga in.&quot;,
            &quot;price&quot;: &quot;1556.86&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/5.png&quot;,
            &quot;level&quot;: &quot;beginner&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 10,
                &quot;name&quot;: &quot;Instructor 5&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 6,
                    &quot;name&quot;: &quot;Nihil&quot;,
                    &quot;slug&quot;: &quot;earum&quot;
                }
            ],
            &quot;enrollments_count&quot;: 1,
            &quot;rating&quot;: 2.8889,
            &quot;reviews_count&quot;: 9,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: true,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 16,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Iste est ratione voluptatem dolorem.&quot;,
                    &quot;duration_in_seconds&quot;: 356,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/1.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/1.jpg&quot;
                },
                {
                    &quot;id&quot;: 17,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Ipsa maxime et expedita dignissimos voluptatem praesentium sunt tempora.&quot;,
                    &quot;duration_in_seconds&quot;: 318,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/2.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/2.jpg&quot;
                },
                {
                    &quot;id&quot;: 18,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Nihil minus temporibus esse vitae quae.&quot;,
                    &quot;duration_in_seconds&quot;: 518,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/3.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/3.jpg&quot;
                },
                {
                    &quot;id&quot;: 19,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Asperiores nobis architecto aut est.&quot;,
                    &quot;duration_in_seconds&quot;: 485,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/4.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/4.jpg&quot;
                },
                {
                    &quot;id&quot;: 20,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Dolorem quisquam eligendi in quam voluptas aut itaque.&quot;,
                    &quot;duration_in_seconds&quot;: 737,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/5.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/5.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 5,
            &quot;title&quot;: &quot;Course 5: Ab&quot;,
            &quot;slug&quot;: &quot;course-5-kfqHL&quot;,
            &quot;description&quot;: &quot;&lt;p&gt;Dolor voluptatem in nesciunt voluptas. Asperiores autem ea impedit tenetur consequatur reprehenderit. Aut sunt officiis hic recusandae. Veniam impedit sit quod omnis.&lt;/p&gt;&quot;,
            &quot;price&quot;: &quot;170.00&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/6.png&quot;,
            &quot;level&quot;: &quot;intermediate&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 10,
                &quot;name&quot;: &quot;Instructor 5&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 6,
                    &quot;name&quot;: &quot;Nihil&quot;,
                    &quot;slug&quot;: &quot;earum&quot;
                }
            ],
            &quot;enrollments_count&quot;: 1,
            &quot;rating&quot;: 2.6,
            &quot;reviews_count&quot;: 10,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: false,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 21,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Reiciendis eos quis aliquam nam consequatur neque.&quot;,
                    &quot;duration_in_seconds&quot;: 529,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/6.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/6.jpg&quot;
                },
                {
                    &quot;id&quot;: 22,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Eum voluptates a temporibus consequuntur.&quot;,
                    &quot;duration_in_seconds&quot;: 692,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/7.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/7.jpg&quot;
                },
                {
                    &quot;id&quot;: 23,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Voluptatem id esse est aliquam corrupti ipsam.&quot;,
                    &quot;duration_in_seconds&quot;: 570,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/8.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/8.jpg&quot;
                },
                {
                    &quot;id&quot;: 24,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Consequuntur dolorem cupiditate fugiat laborum dolores unde.&quot;,
                    &quot;duration_in_seconds&quot;: 629,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/9.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/9.jpg&quot;
                },
                {
                    &quot;id&quot;: 25,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Sint omnis quo nihil tenetur esse aut.&quot;,
                    &quot;duration_in_seconds&quot;: 860,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/10.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/10.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 6,
            &quot;title&quot;: &quot;Course 6: Sint&quot;,
            &quot;slug&quot;: &quot;course-6-oS2lP&quot;,
            &quot;description&quot;: &quot;Qui ratione tempora rem quisquam reprehenderit neque cumque assumenda. Autem sit amet unde nihil eum dolor a. Vero non officia ducimus soluta accusamus.&quot;,
            &quot;price&quot;: &quot;1286.29&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/7.png&quot;,
            &quot;level&quot;: &quot;beginner&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 9,
                &quot;name&quot;: &quot;Instructor 4&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 4,
                    &quot;name&quot;: &quot;Cumque&quot;,
                    &quot;slug&quot;: &quot;dolorem&quot;
                }
            ],
            &quot;enrollments_count&quot;: 0,
            &quot;rating&quot;: 3.8,
            &quot;reviews_count&quot;: 5,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: true,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 26,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Exercitationem iste non nemo voluptates cumque voluptas id.&quot;,
                    &quot;duration_in_seconds&quot;: 707,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/11.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/11.jpg&quot;
                },
                {
                    &quot;id&quot;: 27,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Assumenda dolor assumenda id voluptates itaque assumenda aut.&quot;,
                    &quot;duration_in_seconds&quot;: 402,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/12.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/12.jpg&quot;
                },
                {
                    &quot;id&quot;: 28,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Eos molestias vero quia consequatur porro aliquam dolore.&quot;,
                    &quot;duration_in_seconds&quot;: 463,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/13.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/13.jpg&quot;
                },
                {
                    &quot;id&quot;: 29,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Illo voluptatem voluptatem omnis aspernatur ad.&quot;,
                    &quot;duration_in_seconds&quot;: 398,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/14.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/14.jpg&quot;
                },
                {
                    &quot;id&quot;: 30,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Ea voluptatem doloribus commodi quod dolores illo harum.&quot;,
                    &quot;duration_in_seconds&quot;: 166,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/15.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/15.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 7,
            &quot;title&quot;: &quot;Course 7: Est&quot;,
            &quot;slug&quot;: &quot;course-7-hMgTj&quot;,
            &quot;description&quot;: &quot;Enim sit et rerum qui esse sint consequatur. Eaque minima similique dolor repellendus et. Consequatur blanditiis asperiores repudiandae veritatis error.&quot;,
            &quot;price&quot;: &quot;811.42&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/8.png&quot;,
            &quot;level&quot;: &quot;advanced&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 10,
                &quot;name&quot;: &quot;Instructor 5&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 4,
                    &quot;name&quot;: &quot;Cumque&quot;,
                    &quot;slug&quot;: &quot;dolorem&quot;
                }
            ],
            &quot;enrollments_count&quot;: 1,
            &quot;rating&quot;: 3.5,
            &quot;reviews_count&quot;: 10,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: true,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 31,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Sunt temporibus molestiae sed recusandae veritatis sit.&quot;,
                    &quot;duration_in_seconds&quot;: 154,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/1.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/1.jpg&quot;
                },
                {
                    &quot;id&quot;: 32,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Et et enim ut ipsam aut at.&quot;,
                    &quot;duration_in_seconds&quot;: 777,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/2.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/2.jpg&quot;
                },
                {
                    &quot;id&quot;: 33,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Voluptates sit voluptatem natus quia et commodi.&quot;,
                    &quot;duration_in_seconds&quot;: 697,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/3.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/3.jpg&quot;
                },
                {
                    &quot;id&quot;: 34,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Tempore non tempora libero praesentium.&quot;,
                    &quot;duration_in_seconds&quot;: 864,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/4.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/4.jpg&quot;
                },
                {
                    &quot;id&quot;: 35,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Et culpa ut omnis aut nesciunt magni fugiat quidem.&quot;,
                    &quot;duration_in_seconds&quot;: 408,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/5.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/5.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 8,
            &quot;title&quot;: &quot;Course 8: Omnis&quot;,
            &quot;slug&quot;: &quot;course-8-pdNUr&quot;,
            &quot;description&quot;: &quot;Quos et eveniet est dolorem animi est voluptas. Ullam reprehenderit eaque qui beatae enim nostrum. Accusamus aut facere voluptatum excepturi. Est voluptatem qui dolores molestiae.&quot;,
            &quot;price&quot;: &quot;1519.94&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/9.png&quot;,
            &quot;level&quot;: &quot;intermediate&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 6,
                &quot;name&quot;: &quot;Instructor 1&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 5,
                    &quot;name&quot;: &quot;Blanditiis&quot;,
                    &quot;slug&quot;: &quot;optio&quot;
                }
            ],
            &quot;enrollments_count&quot;: 0,
            &quot;rating&quot;: 4.1,
            &quot;reviews_count&quot;: 10,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: false,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 36,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Voluptas quia aut a.&quot;,
                    &quot;duration_in_seconds&quot;: 737,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/6.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/6.jpg&quot;
                },
                {
                    &quot;id&quot;: 37,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Aut doloremque asperiores delectus et atque molestiae veniam.&quot;,
                    &quot;duration_in_seconds&quot;: 839,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/7.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/7.jpg&quot;
                },
                {
                    &quot;id&quot;: 38,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Optio non maxime aut minima qui.&quot;,
                    &quot;duration_in_seconds&quot;: 757,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/8.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/8.jpg&quot;
                },
                {
                    &quot;id&quot;: 39,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Et ad voluptas reiciendis rerum cum.&quot;,
                    &quot;duration_in_seconds&quot;: 789,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/9.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/9.jpg&quot;
                },
                {
                    &quot;id&quot;: 40,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Rerum quae similique consequuntur autem sunt ipsam dolorum.&quot;,
                    &quot;duration_in_seconds&quot;: 370,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/10.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/10.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 9,
            &quot;title&quot;: &quot;Course 9: Animi&quot;,
            &quot;slug&quot;: &quot;course-9-OGv6r&quot;,
            &quot;description&quot;: &quot;Id vel tenetur quibusdam optio neque nobis. Molestias voluptate non ut voluptatibus incidunt. Minus ex sapiente incidunt voluptas eaque. Est eum exercitationem ipsa quia aut vitae.&quot;,
            &quot;price&quot;: &quot;1617.73&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/10.png&quot;,
            &quot;level&quot;: &quot;beginner&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 10,
                &quot;name&quot;: &quot;Instructor 5&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 7,
                    &quot;name&quot;: &quot;Distinctio&quot;,
                    &quot;slug&quot;: &quot;atque&quot;
                }
            ],
            &quot;enrollments_count&quot;: 0,
            &quot;rating&quot;: 3.3,
            &quot;reviews_count&quot;: 10,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: true,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 41,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Et ut repellendus minima recusandae nobis.&quot;,
                    &quot;duration_in_seconds&quot;: 509,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/11.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/11.jpg&quot;
                },
                {
                    &quot;id&quot;: 42,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Perferendis qui ut necessitatibus voluptatum aut illo rerum.&quot;,
                    &quot;duration_in_seconds&quot;: 217,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/12.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/12.jpg&quot;
                },
                {
                    &quot;id&quot;: 43,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Repudiandae nemo sequi vel exercitationem.&quot;,
                    &quot;duration_in_seconds&quot;: 791,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/13.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/13.jpg&quot;
                },
                {
                    &quot;id&quot;: 44,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Quae laudantium inventore quia eos.&quot;,
                    &quot;duration_in_seconds&quot;: 779,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/14.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/14.jpg&quot;
                },
                {
                    &quot;id&quot;: 45,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Accusamus enim et autem et tenetur numquam.&quot;,
                    &quot;duration_in_seconds&quot;: 424,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/15.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/15.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 10,
            &quot;title&quot;: &quot;Course 10: Eos&quot;,
            &quot;slug&quot;: &quot;course-10-YH2Cb&quot;,
            &quot;description&quot;: &quot;Eum quae officiis delectus quidem ex quos. Qui labore eos et. Maiores sint sunt odio optio ratione. Aut et minus ipsam perspiciatis aut esse mollitia. Sit dolores aut sit.&quot;,
            &quot;price&quot;: &quot;1934.42&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/11.png&quot;,
            &quot;level&quot;: &quot;advanced&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 7,
                &quot;name&quot;: &quot;Instructor 2&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 6,
                    &quot;name&quot;: &quot;Nihil&quot;,
                    &quot;slug&quot;: &quot;earum&quot;
                }
            ],
            &quot;enrollments_count&quot;: 0,
            &quot;rating&quot;: 2.6,
            &quot;reviews_count&quot;: 10,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: true,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 46,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Autem quod architecto eveniet et a explicabo.&quot;,
                    &quot;duration_in_seconds&quot;: 567,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/1.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/1.jpg&quot;
                },
                {
                    &quot;id&quot;: 47,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Ut itaque debitis suscipit illum ut veritatis ducimus.&quot;,
                    &quot;duration_in_seconds&quot;: 368,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/2.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/2.jpg&quot;
                },
                {
                    &quot;id&quot;: 48,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Consequatur earum et qui voluptatem id.&quot;,
                    &quot;duration_in_seconds&quot;: 886,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/3.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/3.jpg&quot;
                },
                {
                    &quot;id&quot;: 49,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Earum enim et impedit recusandae qui quos.&quot;,
                    &quot;duration_in_seconds&quot;: 488,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/4.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/4.jpg&quot;
                },
                {
                    &quot;id&quot;: 50,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Repellat consequuntur blanditiis maiores molestiae.&quot;,
                    &quot;duration_in_seconds&quot;: 627,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/5.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/5.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 11,
            &quot;title&quot;: &quot;Course 11: Quidem&quot;,
            &quot;slug&quot;: &quot;course-11-3773S&quot;,
            &quot;description&quot;: &quot;Et fugit optio et et unde cumque corrupti. Ipsam harum sit quia modi temporibus nesciunt. Veritatis deserunt repellendus maiores consectetur delectus et possimus. Dolorem dolorum expedita nemo dicta accusamus esse facere sint.&quot;,
            &quot;price&quot;: &quot;247.12&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/12.png&quot;,
            &quot;level&quot;: &quot;intermediate&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 7,
                &quot;name&quot;: &quot;Instructor 2&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 8,
                    &quot;name&quot;: &quot;Voluptates&quot;,
                    &quot;slug&quot;: &quot;similique&quot;
                }
            ],
            &quot;enrollments_count&quot;: 0,
            &quot;rating&quot;: 2.8,
            &quot;reviews_count&quot;: 10,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: false,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 51,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Aut accusamus sunt est nulla.&quot;,
                    &quot;duration_in_seconds&quot;: 446,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/6.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/6.jpg&quot;
                },
                {
                    &quot;id&quot;: 52,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;Quo eos nesciunt culpa voluptatem.&quot;,
                    &quot;duration_in_seconds&quot;: 738,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/7.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/7.jpg&quot;
                },
                {
                    &quot;id&quot;: 53,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Praesentium occaecati quia sequi consectetur odit sunt voluptas.&quot;,
                    &quot;duration_in_seconds&quot;: 348,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/8.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/8.jpg&quot;
                },
                {
                    &quot;id&quot;: 54,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Quis et voluptatem ea quasi ut officiis.&quot;,
                    &quot;duration_in_seconds&quot;: 860,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/9.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/9.jpg&quot;
                },
                {
                    &quot;id&quot;: 55,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Doloribus dolor dolore molestias fuga nam eius.&quot;,
                    &quot;duration_in_seconds&quot;: 301,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/10.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/10.jpg&quot;
                }
            ]
        },
        {
            &quot;id&quot;: 12,
            &quot;title&quot;: &quot;Course 12: Est&quot;,
            &quot;slug&quot;: &quot;course-12-gl3Sa&quot;,
            &quot;description&quot;: &quot;Minima mollitia hic et necessitatibus soluta doloremque. Aut praesentium et et rem. Delectus omnis aut est iure sed est maiores. Vel quia velit eos id est qui.&quot;,
            &quot;price&quot;: &quot;1547.33&quot;,
            &quot;image&quot;: &quot;http://127.0.0.1:8000/storage/courses/13.png&quot;,
            &quot;level&quot;: &quot;advanced&quot;,
            &quot;duration&quot;: null,
            &quot;instructor&quot;: {
                &quot;id&quot;: 7,
                &quot;name&quot;: &quot;Instructor 2&quot;,
                &quot;profile_photo_path&quot;: null
            },
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 6,
                    &quot;name&quot;: &quot;Nihil&quot;,
                    &quot;slug&quot;: &quot;earum&quot;
                }
            ],
            &quot;enrollments_count&quot;: 1,
            &quot;rating&quot;: 3,
            &quot;reviews_count&quot;: 8,
            &quot;lessons_count&quot;: 5,
            &quot;is_enrolled&quot;: false,
            &quot;enrollment_status&quot;: null,
            &quot;requires_subscription&quot;: true,
            &quot;can_access&quot;: false,
            &quot;is_wishlisted&quot;: false,
            &quot;wishlist_count&quot;: 0,
            &quot;videos&quot;: [
                {
                    &quot;id&quot;: 56,
                    &quot;title&quot;: &quot;Video 1&quot;,
                    &quot;description&quot;: &quot;Quasi nemo provident et ut possimus.&quot;,
                    &quot;duration_in_seconds&quot;: 697,
                    &quot;sort_order&quot;: 1,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/11.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/11.jpg&quot;
                },
                {
                    &quot;id&quot;: 57,
                    &quot;title&quot;: &quot;Video 2&quot;,
                    &quot;description&quot;: &quot;In est ut asperiores iure quam.&quot;,
                    &quot;duration_in_seconds&quot;: 827,
                    &quot;sort_order&quot;: 2,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/12.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/12.jpg&quot;
                },
                {
                    &quot;id&quot;: 58,
                    &quot;title&quot;: &quot;Video 3&quot;,
                    &quot;description&quot;: &quot;Et consequuntur sit et sed illo.&quot;,
                    &quot;duration_in_seconds&quot;: 427,
                    &quot;sort_order&quot;: 3,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/13.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/13.jpg&quot;
                },
                {
                    &quot;id&quot;: 59,
                    &quot;title&quot;: &quot;Video 4&quot;,
                    &quot;description&quot;: &quot;Quos consequatur consequatur ut et ipsam quia quis esse.&quot;,
                    &quot;duration_in_seconds&quot;: 603,
                    &quot;sort_order&quot;: 4,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/14.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/14.jpg&quot;
                },
                {
                    &quot;id&quot;: 60,
                    &quot;title&quot;: &quot;Video 5&quot;,
                    &quot;description&quot;: &quot;Voluptas quo nostrum non quos.&quot;,
                    &quot;duration_in_seconds&quot;: 748,
                    &quot;sort_order&quot;: 5,
                    &quot;video_url&quot;: &quot;http://127.0.0.1:8000/storage/videos/15.mp4&quot;,
                    &quot;thumbnail_url&quot;: &quot;http://127.0.0.1:8000/storage/video-thumbnails/15.jpg&quot;
                }
            ]
        }
    ],
    &quot;pagination&quot;: {
        &quot;total&quot;: 20,
        &quot;per_page&quot;: 12,
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 2
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-courses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-courses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-courses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-courses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-courses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-courses" data-method="GET"
      data-path="api/courses"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-courses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-courses"
                    onclick="tryItOut('GETapi-courses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-courses"
                    onclick="cancelTryOut('GETapi-courses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-courses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/courses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-courses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-courses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-courses--slug-">GET api/courses/{slug}</h2>

<p>
</p>



<span id="example-requests-GETapi-courses--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/courses/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-courses--slug-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Course].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-courses--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-courses--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-courses--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-courses--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-courses--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-courses--slug-" data-method="GET"
      data-path="api/courses/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-courses--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-courses--slug-"
                    onclick="tryItOut('GETapi-courses--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-courses--slug-"
                    onclick="cancelTryOut('GETapi-courses--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-courses--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/courses/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-courses--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-courses--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="slug"                data-endpoint="GETapi-courses--slug-"
               value="1"
               data-component="url">
    <br>
<p>The slug of the course. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-instructors">GET api/instructors</h2>

<p>
</p>



<span id="example-requests-GETapi-instructors">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/instructors" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/instructors"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-instructors">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-instructors" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-instructors"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-instructors"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-instructors" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-instructors">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-instructors" data-method="GET"
      data-path="api/instructors"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-instructors', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-instructors"
                    onclick="tryItOut('GETapi-instructors');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-instructors"
                    onclick="cancelTryOut('GETapi-instructors');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-instructors"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/instructors</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-instructors"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-instructors"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-levels">GET api/levels</h2>

<p>
</p>



<span id="example-requests-GETapi-levels">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/levels" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/levels"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-levels">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: &quot;intermediate&quot;,
        &quot;name&quot;: &quot;Intermediate&quot;
    },
    {
        &quot;id&quot;: &quot;beginner&quot;,
        &quot;name&quot;: &quot;Beginner&quot;
    },
    {
        &quot;id&quot;: &quot;advanced&quot;,
        &quot;name&quot;: &quot;Advanced&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-levels" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-levels"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-levels"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-levels" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-levels">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-levels" data-method="GET"
      data-path="api/levels"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-levels', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-levels"
                    onclick="tryItOut('GETapi-levels');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-levels"
                    onclick="cancelTryOut('GETapi-levels');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-levels"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/levels</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-levels"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-levels"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-plans">GET api/plans</h2>

<p>
</p>



<span id="example-requests-GETapi-plans">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/plans" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/plans"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-plans">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;4&quot;,
            &quot;slug&quot;: &quot;4&quot;,
            &quot;description&quot;: &quot;4&quot;,
            &quot;price&quot;: &quot;444.00&quot;,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;interval&quot;: &quot;month&quot;,
            &quot;interval_count&quot;: 1,
            &quot;is_active&quot;: true,
            &quot;created_at&quot;: &quot;2025-11-14T06:06:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-14T06:06:49.000000Z&quot;,
            &quot;courses_count&quot;: 0
        },
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Basic&quot;,
            &quot;slug&quot;: &quot;basic&quot;,
            &quot;description&quot;: &quot;Access to basic courses&quot;,
            &quot;price&quot;: &quot;499.00&quot;,
            &quot;currency&quot;: &quot;INR&quot;,
            &quot;interval&quot;: &quot;month&quot;,
            &quot;interval_count&quot;: 1,
            &quot;is_active&quot;: true,
            &quot;created_at&quot;: &quot;2025-11-12T06:41:23.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-12T06:41:23.000000Z&quot;,
            &quot;courses_count&quot;: 7
        },
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Pro&quot;,
            &quot;slug&quot;: &quot;pro&quot;,
            &quot;description&quot;: &quot;Extended course library&quot;,
            &quot;price&quot;: &quot;999.00&quot;,
            &quot;currency&quot;: &quot;INR&quot;,
            &quot;interval&quot;: &quot;month&quot;,
            &quot;interval_count&quot;: 3,
            &quot;is_active&quot;: true,
            &quot;created_at&quot;: &quot;2025-11-12T06:41:23.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-12T06:41:23.000000Z&quot;,
            &quot;courses_count&quot;: 6
        },
        {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Premium&quot;,
            &quot;slug&quot;: &quot;premium&quot;,
            &quot;description&quot;: &quot;Full access &amp; priority support&quot;,
            &quot;price&quot;: &quot;1999.00&quot;,
            &quot;currency&quot;: &quot;INR&quot;,
            &quot;interval&quot;: &quot;year&quot;,
            &quot;interval_count&quot;: 1,
            &quot;is_active&quot;: true,
            &quot;created_at&quot;: &quot;2025-11-12T06:41:23.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-12T06:41:23.000000Z&quot;,
            &quot;courses_count&quot;: 3
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-plans" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-plans"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-plans"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-plans" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-plans">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-plans" data-method="GET"
      data-path="api/plans"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-plans', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-plans"
                    onclick="tryItOut('GETapi-plans');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-plans"
                    onclick="cancelTryOut('GETapi-plans');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-plans"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/plans</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-plans"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-plans"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-plans--slug-">GET api/plans/{slug}</h2>

<p>
</p>



<span id="example-requests-GETapi-plans--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/plans/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/plans/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-plans--slug-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Plan].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-plans--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-plans--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-plans--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-plans--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-plans--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-plans--slug-" data-method="GET"
      data-path="api/plans/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-plans--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-plans--slug-"
                    onclick="tryItOut('GETapi-plans--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-plans--slug-"
                    onclick="cancelTryOut('GETapi-plans--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-plans--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/plans/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-plans--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-plans--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="slug"                data-endpoint="GETapi-plans--slug-"
               value="1"
               data-component="url">
    <br>
<p>The slug of the plan. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-subscriptions-popular-plan">GET api/subscriptions/popular-plan</h2>

<p>
</p>



<span id="example-requests-GETapi-subscriptions-popular-plan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/subscriptions/popular-plan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/subscriptions/popular-plan"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-subscriptions-popular-plan">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;plan_id&quot;: 1,
    &quot;subscription_count&quot;: 1
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-subscriptions-popular-plan" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-subscriptions-popular-plan"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-subscriptions-popular-plan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-subscriptions-popular-plan" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-subscriptions-popular-plan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-subscriptions-popular-plan" data-method="GET"
      data-path="api/subscriptions/popular-plan"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-subscriptions-popular-plan', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-subscriptions-popular-plan"
                    onclick="tryItOut('GETapi-subscriptions-popular-plan');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-subscriptions-popular-plan"
                    onclick="cancelTryOut('GETapi-subscriptions-popular-plan');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-subscriptions-popular-plan"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/subscriptions/popular-plan</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-subscriptions-popular-plan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-subscriptions-popular-plan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-courses--course_id--reviews">Get all reviews for a specific course.</h2>

<p>
</p>



<span id="example-requests-GETapi-courses--course_id--reviews">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/courses/1/reviews" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/reviews"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-courses--course_id--reviews">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-courses--course_id--reviews" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-courses--course_id--reviews"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-courses--course_id--reviews"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-courses--course_id--reviews" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-courses--course_id--reviews">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-courses--course_id--reviews" data-method="GET"
      data-path="api/courses/{course_id}/reviews"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-courses--course_id--reviews', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-courses--course_id--reviews"
                    onclick="tryItOut('GETapi-courses--course_id--reviews');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-courses--course_id--reviews"
                    onclick="cancelTryOut('GETapi-courses--course_id--reviews');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-courses--course_id--reviews"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/courses/{course_id}/reviews</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-courses--course_id--reviews"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-courses--course_id--reviews"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>course_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="course_id"                data-endpoint="GETapi-courses--course_id--reviews"
               value="1"
               data-component="url">
    <br>
<p>The ID of the course. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-verify-certificate--certificateNumber-">Verify certificate by certificate number (public route)</h2>

<p>
</p>



<span id="example-requests-GETapi-verify-certificate--certificateNumber-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/verify-certificate/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/verify-certificate/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-verify-certificate--certificateNumber-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;valid&quot;: false,
    &quot;message&quot;: &quot;Certificate not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-verify-certificate--certificateNumber-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-verify-certificate--certificateNumber-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-verify-certificate--certificateNumber-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-verify-certificate--certificateNumber-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-verify-certificate--certificateNumber-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-verify-certificate--certificateNumber-" data-method="GET"
      data-path="api/verify-certificate/{certificateNumber}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-verify-certificate--certificateNumber-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-verify-certificate--certificateNumber-"
                    onclick="tryItOut('GETapi-verify-certificate--certificateNumber-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-verify-certificate--certificateNumber-"
                    onclick="cancelTryOut('GETapi-verify-certificate--certificateNumber-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-verify-certificate--certificateNumber-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/verify-certificate/{certificateNumber}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-verify-certificate--certificateNumber-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-verify-certificate--certificateNumber-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>certificateNumber</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="certificateNumber"                data-endpoint="GETapi-verify-certificate--certificateNumber-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-enrollments">GET api/enrollments</h2>

<p>
</p>



<span id="example-requests-GETapi-enrollments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/enrollments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/enrollments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-enrollments">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-enrollments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-enrollments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-enrollments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-enrollments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-enrollments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-enrollments" data-method="GET"
      data-path="api/enrollments"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-enrollments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-enrollments"
                    onclick="tryItOut('GETapi-enrollments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-enrollments"
                    onclick="cancelTryOut('GETapi-enrollments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-enrollments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/enrollments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-enrollments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-enrollments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-enrollments">POST api/enrollments</h2>

<p>
</p>



<span id="example-requests-POSTapi-enrollments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/enrollments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"course_id\": 17,
    \"payment_id\": \"consequatur\",
    \"order_id\": \"consequatur\",
    \"signature\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/enrollments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "course_id": 17,
    "payment_id": "consequatur",
    "order_id": "consequatur",
    "signature": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-enrollments">
</span>
<span id="execution-results-POSTapi-enrollments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-enrollments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-enrollments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-enrollments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-enrollments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-enrollments" data-method="POST"
      data-path="api/enrollments"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-enrollments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-enrollments"
                    onclick="tryItOut('POSTapi-enrollments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-enrollments"
                    onclick="cancelTryOut('POSTapi-enrollments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-enrollments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/enrollments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-enrollments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-enrollments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>course_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="course_id"                data-endpoint="POSTapi-enrollments"
               value="17"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the courses table. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_id"                data-endpoint="POSTapi-enrollments"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>order_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_id"                data-endpoint="POSTapi-enrollments"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>signature</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="signature"                data-endpoint="POSTapi-enrollments"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-dashboard">GET api/dashboard</h2>

<p>
</p>



<span id="example-requests-GETapi-dashboard">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/dashboard" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/dashboard"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-dashboard">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-dashboard" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-dashboard"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-dashboard"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-dashboard" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-dashboard">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-dashboard" data-method="GET"
      data-path="api/dashboard"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-dashboard', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-dashboard"
                    onclick="tryItOut('GETapi-dashboard');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-dashboard"
                    onclick="cancelTryOut('GETapi-dashboard');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-dashboard"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/dashboard</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-dashboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-dashboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-courses--courseId--progress">Get course progress for authenticated user</h2>

<p>
</p>



<span id="example-requests-GETapi-courses--courseId--progress">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/courses/1/progress" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/progress"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-courses--courseId--progress">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-courses--courseId--progress" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-courses--courseId--progress"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-courses--courseId--progress"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-courses--courseId--progress" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-courses--courseId--progress">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-courses--courseId--progress" data-method="GET"
      data-path="api/courses/{courseId}/progress"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-courses--courseId--progress', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-courses--courseId--progress"
                    onclick="tryItOut('GETapi-courses--courseId--progress');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-courses--courseId--progress"
                    onclick="cancelTryOut('GETapi-courses--courseId--progress');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-courses--courseId--progress"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/courses/{courseId}/progress</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-courses--courseId--progress"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-courses--courseId--progress"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="courseId"                data-endpoint="GETapi-courses--courseId--progress"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-courses--courseId--videos--videoId--complete">Mark video as completed</h2>

<p>
</p>



<span id="example-requests-POSTapi-courses--courseId--videos--videoId--complete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/courses/1/videos/1/complete" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/videos/1/complete"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-courses--courseId--videos--videoId--complete">
</span>
<span id="execution-results-POSTapi-courses--courseId--videos--videoId--complete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-courses--courseId--videos--videoId--complete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-courses--courseId--videos--videoId--complete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-courses--courseId--videos--videoId--complete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-courses--courseId--videos--videoId--complete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-courses--courseId--videos--videoId--complete" data-method="POST"
      data-path="api/courses/{courseId}/videos/{videoId}/complete"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-courses--courseId--videos--videoId--complete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-courses--courseId--videos--videoId--complete"
                    onclick="tryItOut('POSTapi-courses--courseId--videos--videoId--complete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-courses--courseId--videos--videoId--complete"
                    onclick="cancelTryOut('POSTapi-courses--courseId--videos--videoId--complete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-courses--courseId--videos--videoId--complete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/courses/{courseId}/videos/{videoId}/complete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-courses--courseId--videos--videoId--complete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-courses--courseId--videos--videoId--complete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="courseId"                data-endpoint="POSTapi-courses--courseId--videos--videoId--complete"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>videoId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="videoId"                data-endpoint="POSTapi-courses--courseId--videos--videoId--complete"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-courses--courseId--time-spent">Update time spent on course</h2>

<p>
</p>



<span id="example-requests-POSTapi-courses--courseId--time-spent">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/courses/1/time-spent" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/time-spent"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-courses--courseId--time-spent">
</span>
<span id="execution-results-POSTapi-courses--courseId--time-spent" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-courses--courseId--time-spent"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-courses--courseId--time-spent"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-courses--courseId--time-spent" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-courses--courseId--time-spent">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-courses--courseId--time-spent" data-method="POST"
      data-path="api/courses/{courseId}/time-spent"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-courses--courseId--time-spent', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-courses--courseId--time-spent"
                    onclick="tryItOut('POSTapi-courses--courseId--time-spent');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-courses--courseId--time-spent"
                    onclick="cancelTryOut('POSTapi-courses--courseId--time-spent');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-courses--courseId--time-spent"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/courses/{courseId}/time-spent</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-courses--courseId--time-spent"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-courses--courseId--time-spent"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="courseId"                data-endpoint="POSTapi-courses--courseId--time-spent"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-dashboard-progress">Get user&#039;s dashboard progress data</h2>

<p>
</p>



<span id="example-requests-GETapi-dashboard-progress">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/dashboard/progress" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/dashboard/progress"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-dashboard-progress">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-dashboard-progress" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-dashboard-progress"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-dashboard-progress"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-dashboard-progress" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-dashboard-progress">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-dashboard-progress" data-method="GET"
      data-path="api/dashboard/progress"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-dashboard-progress', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-dashboard-progress"
                    onclick="tryItOut('GETapi-dashboard-progress');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-dashboard-progress"
                    onclick="cancelTryOut('GETapi-dashboard-progress');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-dashboard-progress"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/dashboard/progress</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-dashboard-progress"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-dashboard-progress"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-certificates">Get all certificates for the authenticated user</h2>

<p>
</p>



<span id="example-requests-GETapi-certificates">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/certificates" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/certificates"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-certificates">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-certificates" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-certificates"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-certificates"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-certificates" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-certificates">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-certificates" data-method="GET"
      data-path="api/certificates"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-certificates', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-certificates"
                    onclick="tryItOut('GETapi-certificates');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-certificates"
                    onclick="cancelTryOut('GETapi-certificates');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-certificates"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/certificates</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-certificates"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-certificates"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-courses--courseId--certificate-generate">Generate and download certificate for a course</h2>

<p>
</p>



<span id="example-requests-POSTapi-courses--courseId--certificate-generate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/courses/1/certificate/generate" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/certificate/generate"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-courses--courseId--certificate-generate">
</span>
<span id="execution-results-POSTapi-courses--courseId--certificate-generate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-courses--courseId--certificate-generate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-courses--courseId--certificate-generate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-courses--courseId--certificate-generate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-courses--courseId--certificate-generate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-courses--courseId--certificate-generate" data-method="POST"
      data-path="api/courses/{courseId}/certificate/generate"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-courses--courseId--certificate-generate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-courses--courseId--certificate-generate"
                    onclick="tryItOut('POSTapi-courses--courseId--certificate-generate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-courses--courseId--certificate-generate"
                    onclick="cancelTryOut('POSTapi-courses--courseId--certificate-generate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-courses--courseId--certificate-generate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/courses/{courseId}/certificate/generate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-courses--courseId--certificate-generate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-courses--courseId--certificate-generate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="courseId"                data-endpoint="POSTapi-courses--courseId--certificate-generate"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-courses--courseId--certificate-preview">Preview certificate inline</h2>

<p>
</p>



<span id="example-requests-GETapi-courses--courseId--certificate-preview">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/courses/1/certificate/preview" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/certificate/preview"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-courses--courseId--certificate-preview">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-courses--courseId--certificate-preview" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-courses--courseId--certificate-preview"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-courses--courseId--certificate-preview"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-courses--courseId--certificate-preview" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-courses--courseId--certificate-preview">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-courses--courseId--certificate-preview" data-method="GET"
      data-path="api/courses/{courseId}/certificate/preview"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-courses--courseId--certificate-preview', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-courses--courseId--certificate-preview"
                    onclick="tryItOut('GETapi-courses--courseId--certificate-preview');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-courses--courseId--certificate-preview"
                    onclick="cancelTryOut('GETapi-courses--courseId--certificate-preview');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-courses--courseId--certificate-preview"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/courses/{courseId}/certificate/preview</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-courses--courseId--certificate-preview"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-courses--courseId--certificate-preview"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="courseId"                data-endpoint="GETapi-courses--courseId--certificate-preview"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-courses--courseId--certificate-check-eligibility">Check if user is eligible for a certificate</h2>

<p>
</p>



<span id="example-requests-GETapi-courses--courseId--certificate-check-eligibility">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/courses/1/certificate/check-eligibility" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/certificate/check-eligibility"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-courses--courseId--certificate-check-eligibility">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-courses--courseId--certificate-check-eligibility" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-courses--courseId--certificate-check-eligibility"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-courses--courseId--certificate-check-eligibility"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-courses--courseId--certificate-check-eligibility" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-courses--courseId--certificate-check-eligibility">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-courses--courseId--certificate-check-eligibility" data-method="GET"
      data-path="api/courses/{courseId}/certificate/check-eligibility"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-courses--courseId--certificate-check-eligibility', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-courses--courseId--certificate-check-eligibility"
                    onclick="tryItOut('GETapi-courses--courseId--certificate-check-eligibility');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-courses--courseId--certificate-check-eligibility"
                    onclick="cancelTryOut('GETapi-courses--courseId--certificate-check-eligibility');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-courses--courseId--certificate-check-eligibility"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/courses/{courseId}/certificate/check-eligibility</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-courses--courseId--certificate-check-eligibility"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-courses--courseId--certificate-check-eligibility"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="courseId"                data-endpoint="GETapi-courses--courseId--certificate-check-eligibility"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-certificates--certificateId--download">Download certificate by ID</h2>

<p>
</p>



<span id="example-requests-GETapi-certificates--certificateId--download">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/certificates/1/download" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/certificates/1/download"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-certificates--certificateId--download">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-certificates--certificateId--download" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-certificates--certificateId--download"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-certificates--certificateId--download"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-certificates--certificateId--download" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-certificates--certificateId--download">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-certificates--certificateId--download" data-method="GET"
      data-path="api/certificates/{certificateId}/download"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-certificates--certificateId--download', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-certificates--certificateId--download"
                    onclick="tryItOut('GETapi-certificates--certificateId--download');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-certificates--certificateId--download"
                    onclick="cancelTryOut('GETapi-certificates--certificateId--download');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-certificates--certificateId--download"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/certificates/{certificateId}/download</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-certificates--certificateId--download"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-certificates--certificateId--download"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>certificateId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="certificateId"                data-endpoint="GETapi-certificates--certificateId--download"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-test--courseId-">GET api/test/{courseId}</h2>

<p>
</p>



<span id="example-requests-GETapi-test--courseId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/test/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/test/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-test--courseId-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-test--courseId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-test--courseId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-test--courseId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-test--courseId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-test--courseId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-test--courseId-" data-method="GET"
      data-path="api/test/{courseId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-test--courseId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-test--courseId-"
                    onclick="tryItOut('GETapi-test--courseId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-test--courseId-"
                    onclick="cancelTryOut('GETapi-test--courseId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-test--courseId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/test/{courseId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-test--courseId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-test--courseId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="courseId"                data-endpoint="GETapi-test--courseId-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-test-submit">POST api/test/submit</h2>

<p>
</p>



<span id="example-requests-POSTapi-test-submit">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/test/submit" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"course_id\": 17,
    \"answers\": [
        {
            \"question_id\": 17,
            \"selected_option\": \"d\"
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/test/submit"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "course_id": 17,
    "answers": [
        {
            "question_id": 17,
            "selected_option": "d"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-test-submit">
</span>
<span id="execution-results-POSTapi-test-submit" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-test-submit"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-test-submit"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-test-submit" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-test-submit">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-test-submit" data-method="POST"
      data-path="api/test/submit"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-test-submit', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-test-submit"
                    onclick="tryItOut('POSTapi-test-submit');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-test-submit"
                    onclick="cancelTryOut('POSTapi-test-submit');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-test-submit"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/test/submit</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-test-submit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-test-submit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>course_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="course_id"                data-endpoint="POSTapi-test-submit"
               value="17"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the courses table. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>answers</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Must have at least 1 items.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>question_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="answers.0.question_id"                data-endpoint="POSTapi-test-submit"
               value="17"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the test_questions table. Example: <code>17</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>selected_option</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="answers.0.selected_option"                data-endpoint="POSTapi-test-submit"
               value="d"
               data-component="body">
    <br>
<p>Example: <code>d</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>a</code></li> <li><code>b</code></li> <li><code>c</code></li> <li><code>d</code></li></ul>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-test-status--courseId-">GET api/test/status/{courseId}</h2>

<p>
</p>



<span id="example-requests-GETapi-test-status--courseId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/test/status/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/test/status/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-test-status--courseId-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-test-status--courseId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-test-status--courseId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-test-status--courseId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-test-status--courseId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-test-status--courseId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-test-status--courseId-" data-method="GET"
      data-path="api/test/status/{courseId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-test-status--courseId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-test-status--courseId-"
                    onclick="tryItOut('GETapi-test-status--courseId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-test-status--courseId-"
                    onclick="cancelTryOut('GETapi-test-status--courseId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-test-status--courseId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/test/status/{courseId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-test-status--courseId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-test-status--courseId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="courseId"                data-endpoint="GETapi-test-status--courseId-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-certificate-download--courseId-">Download certificate by course ID</h2>

<p>
</p>



<span id="example-requests-GETapi-certificate-download--courseId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/certificate/download/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/certificate/download/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-certificate-download--courseId-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-certificate-download--courseId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-certificate-download--courseId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-certificate-download--courseId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-certificate-download--courseId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-certificate-download--courseId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-certificate-download--courseId-" data-method="GET"
      data-path="api/certificate/download/{courseId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-certificate-download--courseId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-certificate-download--courseId-"
                    onclick="tryItOut('GETapi-certificate-download--courseId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-certificate-download--courseId-"
                    onclick="cancelTryOut('GETapi-certificate-download--courseId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-certificate-download--courseId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/certificate/download/{courseId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-certificate-download--courseId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-certificate-download--courseId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="courseId"                data-endpoint="GETapi-certificate-download--courseId-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-courses--course_id--reviews">Store a new review.</h2>

<p>
</p>



<span id="example-requests-POSTapi-courses--course_id--reviews">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/courses/1/reviews" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"rating\": 5,
    \"comment\": \"mqeopfuudtdsufvyvddqa\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/reviews"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "rating": 5,
    "comment": "mqeopfuudtdsufvyvddqa"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-courses--course_id--reviews">
</span>
<span id="execution-results-POSTapi-courses--course_id--reviews" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-courses--course_id--reviews"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-courses--course_id--reviews"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-courses--course_id--reviews" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-courses--course_id--reviews">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-courses--course_id--reviews" data-method="POST"
      data-path="api/courses/{course_id}/reviews"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-courses--course_id--reviews', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-courses--course_id--reviews"
                    onclick="tryItOut('POSTapi-courses--course_id--reviews');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-courses--course_id--reviews"
                    onclick="cancelTryOut('POSTapi-courses--course_id--reviews');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-courses--course_id--reviews"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/courses/{course_id}/reviews</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-courses--course_id--reviews"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-courses--course_id--reviews"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>course_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="course_id"                data-endpoint="POSTapi-courses--course_id--reviews"
               value="1"
               data-component="url">
    <br>
<p>The ID of the course. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>rating</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="rating"                data-endpoint="POSTapi-courses--course_id--reviews"
               value="5"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 5. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="comment"                data-endpoint="POSTapi-courses--course_id--reviews"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 1000 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
        </form>

                    <h2 id="endpoints-PUTapi-reviews--review_id-">Update the specified review.</h2>

<p>
</p>



<span id="example-requests-PUTapi-reviews--review_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/reviews/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"rating\": 5,
    \"comment\": \"mqeopfuudtdsufvyvddqa\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/reviews/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "rating": 5,
    "comment": "mqeopfuudtdsufvyvddqa"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-reviews--review_id-">
</span>
<span id="execution-results-PUTapi-reviews--review_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-reviews--review_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-reviews--review_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-reviews--review_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-reviews--review_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-reviews--review_id-" data-method="PUT"
      data-path="api/reviews/{review_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-reviews--review_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-reviews--review_id-"
                    onclick="tryItOut('PUTapi-reviews--review_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-reviews--review_id-"
                    onclick="cancelTryOut('PUTapi-reviews--review_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-reviews--review_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/reviews/{review_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-reviews--review_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-reviews--review_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>review_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="review_id"                data-endpoint="PUTapi-reviews--review_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the review. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>rating</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="rating"                data-endpoint="PUTapi-reviews--review_id-"
               value="5"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 5. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="comment"                data-endpoint="PUTapi-reviews--review_id-"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 1000 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
        </form>

                    <h2 id="endpoints-DELETEapi-reviews--review_id-">Remove the specified review.</h2>

<p>
</p>



<span id="example-requests-DELETEapi-reviews--review_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/reviews/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/reviews/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-reviews--review_id-">
</span>
<span id="execution-results-DELETEapi-reviews--review_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-reviews--review_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-reviews--review_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-reviews--review_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-reviews--review_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-reviews--review_id-" data-method="DELETE"
      data-path="api/reviews/{review_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-reviews--review_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-reviews--review_id-"
                    onclick="tryItOut('DELETEapi-reviews--review_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-reviews--review_id-"
                    onclick="cancelTryOut('DELETEapi-reviews--review_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-reviews--review_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/reviews/{review_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-reviews--review_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-reviews--review_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>review_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="review_id"                data-endpoint="DELETEapi-reviews--review_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the review. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-courses--courseId--enrollment-status">Get course enrollment status for a user</h2>

<p>
</p>



<span id="example-requests-GETapi-courses--courseId--enrollment-status">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/courses/1/enrollment-status" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/courses/1/enrollment-status"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-courses--courseId--enrollment-status">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-courses--courseId--enrollment-status" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-courses--courseId--enrollment-status"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-courses--courseId--enrollment-status"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-courses--courseId--enrollment-status" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-courses--courseId--enrollment-status">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-courses--courseId--enrollment-status" data-method="GET"
      data-path="api/courses/{courseId}/enrollment-status"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-courses--courseId--enrollment-status', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-courses--courseId--enrollment-status"
                    onclick="tryItOut('GETapi-courses--courseId--enrollment-status');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-courses--courseId--enrollment-status"
                    onclick="cancelTryOut('GETapi-courses--courseId--enrollment-status');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-courses--courseId--enrollment-status"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/courses/{courseId}/enrollment-status</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-courses--courseId--enrollment-status"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-courses--courseId--enrollment-status"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>courseId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="courseId"                data-endpoint="GETapi-courses--courseId--enrollment-status"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-create-order">GET api/create-order</h2>

<p>
</p>



<span id="example-requests-GETapi-create-order">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/create-order" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/create-order"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-create-order">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Error creating Razorpay order&quot;,
    &quot;error&quot;: &quot;Order amount less than minimum amount allowed&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-create-order" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-create-order"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-create-order"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-create-order" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-create-order">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-create-order" data-method="GET"
      data-path="api/create-order"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-create-order', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-create-order"
                    onclick="tryItOut('GETapi-create-order');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-create-order"
                    onclick="cancelTryOut('GETapi-create-order');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-create-order"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/create-order</code></b>
        </p>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/create-order</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-create-order"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-create-order"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-enrollments-check">POST api/enrollments/check</h2>

<p>
</p>



<span id="example-requests-POSTapi-enrollments-check">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/enrollments/check" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"course_id\": 17
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/enrollments/check"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "course_id": 17
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-enrollments-check">
</span>
<span id="execution-results-POSTapi-enrollments-check" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-enrollments-check"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-enrollments-check"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-enrollments-check" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-enrollments-check">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-enrollments-check" data-method="POST"
      data-path="api/enrollments/check"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-enrollments-check', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-enrollments-check"
                    onclick="tryItOut('POSTapi-enrollments-check');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-enrollments-check"
                    onclick="cancelTryOut('POSTapi-enrollments-check');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-enrollments-check"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/enrollments/check</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-enrollments-check"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-enrollments-check"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>course_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="course_id"                data-endpoint="POSTapi-enrollments-check"
               value="17"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the courses table. Example: <code>17</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-subscriptions-subscribe">POST api/subscriptions/subscribe</h2>

<p>
</p>



<span id="example-requests-POSTapi-subscriptions-subscribe">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/subscriptions/subscribe" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"plan_id\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/subscriptions/subscribe"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "plan_id": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-subscriptions-subscribe">
</span>
<span id="execution-results-POSTapi-subscriptions-subscribe" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-subscriptions-subscribe"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions-subscribe"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-subscriptions-subscribe" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions-subscribe">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-subscriptions-subscribe" data-method="POST"
      data-path="api/subscriptions/subscribe"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions-subscribe', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-subscriptions-subscribe"
                    onclick="tryItOut('POSTapi-subscriptions-subscribe');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-subscriptions-subscribe"
                    onclick="cancelTryOut('POSTapi-subscriptions-subscribe');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-subscriptions-subscribe"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/subscriptions/subscribe</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-subscriptions-subscribe"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-subscriptions-subscribe"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plan_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plan_id"                data-endpoint="POSTapi-subscriptions-subscribe"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the plans table. Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-subscriptions-cancel">POST api/subscriptions/cancel</h2>

<p>
</p>



<span id="example-requests-POSTapi-subscriptions-cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/subscriptions/cancel" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/subscriptions/cancel"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-subscriptions-cancel">
</span>
<span id="execution-results-POSTapi-subscriptions-cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-subscriptions-cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions-cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-subscriptions-cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions-cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-subscriptions-cancel" data-method="POST"
      data-path="api/subscriptions/cancel"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions-cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-subscriptions-cancel"
                    onclick="tryItOut('POSTapi-subscriptions-cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-subscriptions-cancel"
                    onclick="cancelTryOut('POSTapi-subscriptions-cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-subscriptions-cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/subscriptions/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-subscriptions-cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-subscriptions-cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-subscriptions-status">GET api/subscriptions/status</h2>

<p>
</p>



<span id="example-requests-GETapi-subscriptions-status">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/subscriptions/status" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/subscriptions/status"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-subscriptions-status">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-subscriptions-status" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-subscriptions-status"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-subscriptions-status"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-subscriptions-status" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-subscriptions-status">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-subscriptions-status" data-method="GET"
      data-path="api/subscriptions/status"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-subscriptions-status', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-subscriptions-status"
                    onclick="tryItOut('GETapi-subscriptions-status');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-subscriptions-status"
                    onclick="cancelTryOut('GETapi-subscriptions-status');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-subscriptions-status"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/subscriptions/status</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-subscriptions-status"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-subscriptions-status"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-subscriptions-courses">GET api/subscriptions/courses</h2>

<p>
</p>



<span id="example-requests-GETapi-subscriptions-courses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/subscriptions/courses" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/subscriptions/courses"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-subscriptions-courses">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-subscriptions-courses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-subscriptions-courses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-subscriptions-courses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-subscriptions-courses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-subscriptions-courses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-subscriptions-courses" data-method="GET"
      data-path="api/subscriptions/courses"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-subscriptions-courses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-subscriptions-courses"
                    onclick="tryItOut('GETapi-subscriptions-courses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-subscriptions-courses"
                    onclick="cancelTryOut('GETapi-subscriptions-courses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-subscriptions-courses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/subscriptions/courses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-subscriptions-courses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-subscriptions-courses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-subscriptions">GET api/subscriptions</h2>

<p>
</p>



<span id="example-requests-GETapi-subscriptions">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/subscriptions" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/subscriptions"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-subscriptions">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-subscriptions" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-subscriptions"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-subscriptions"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-subscriptions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-subscriptions">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-subscriptions" data-method="GET"
      data-path="api/subscriptions"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-subscriptions', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-subscriptions"
                    onclick="tryItOut('GETapi-subscriptions');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-subscriptions"
                    onclick="cancelTryOut('GETapi-subscriptions');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-subscriptions"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/subscriptions</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-subscriptions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-subscriptions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-wishlist">GET api/wishlist</h2>

<p>
</p>



<span id="example-requests-GETapi-wishlist">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/wishlist" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/wishlist"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-wishlist">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-wishlist" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-wishlist"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-wishlist"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-wishlist" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-wishlist">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-wishlist" data-method="GET"
      data-path="api/wishlist"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-wishlist', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-wishlist"
                    onclick="tryItOut('GETapi-wishlist');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-wishlist"
                    onclick="cancelTryOut('GETapi-wishlist');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-wishlist"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/wishlist</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-wishlist"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-wishlist"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-wishlist">POST api/wishlist</h2>

<p>
</p>



<span id="example-requests-POSTapi-wishlist">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/wishlist" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/wishlist"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-wishlist">
</span>
<span id="execution-results-POSTapi-wishlist" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-wishlist"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-wishlist"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-wishlist" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-wishlist">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-wishlist" data-method="POST"
      data-path="api/wishlist"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-wishlist', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-wishlist"
                    onclick="tryItOut('POSTapi-wishlist');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-wishlist"
                    onclick="cancelTryOut('POSTapi-wishlist');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-wishlist"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/wishlist</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-wishlist"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-wishlist"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-DELETEapi-wishlist--course-">DELETE api/wishlist/{course}</h2>

<p>
</p>



<span id="example-requests-DELETEapi-wishlist--course-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/wishlist/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/wishlist/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-wishlist--course-">
</span>
<span id="execution-results-DELETEapi-wishlist--course-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-wishlist--course-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-wishlist--course-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-wishlist--course-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-wishlist--course-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-wishlist--course-" data-method="DELETE"
      data-path="api/wishlist/{course}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-wishlist--course-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-wishlist--course-"
                    onclick="tryItOut('DELETEapi-wishlist--course-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-wishlist--course-"
                    onclick="cancelTryOut('DELETEapi-wishlist--course-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-wishlist--course-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/wishlist/{course}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-wishlist--course-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-wishlist--course-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>course</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="course"                data-endpoint="DELETEapi-wishlist--course-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
