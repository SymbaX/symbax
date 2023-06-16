<!DOCTYPE html>
<html lang="ja">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <style>
    body {
      background-color: #d6b28d;
      /* 砂の色（濃い目） */
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .error-wrap {
      background-color: #f7f5f3;
      /* 砂漠の地面の色 */
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
      padding: 20px;
      max-width: 500px;
      text-align: center;
    }

    h1 {
      font-size: 24px;
      margin-bottom: 10px;
      color: #c1833e;
      /* 砂漠の砂の色（濃い目） */
    }

    p {
      font-size: 14px;
      margin-bottom: 20px;
      color: #777;
    }

    .error-detail {
      font-size: 12px;
      color: #999;
    }

    .link {
      display: inline-block;
      margin-top: 20px;
    }

    .link a {
      color: #007bff;
      text-decoration: none;
    }

    .link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="error-wrap">
    <section>
      <h1>@yield('title')</h1>
      <p class="error-message">@yield('message')</p>
      <p class="error-detail">@yield('detail')</p>
      <div class="link">
        @yield('link')
      </div>
    </section>
  </div>
</body>

</html>