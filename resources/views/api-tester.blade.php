<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="token" content="{{ csrf_token() }}">
    <meta name="firebase" content="{{ $firebaseToken }}" >
    <base href="{{ url(config('api-tester.route')).'/' }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ route('api-tester.file', ['file' => 'api-tester.css']) }}">
    <title>Laravel api tester</title>
</head>
<body>
<div id="api-tester">
    <vm-api-tester-main></vm-api-tester-main>
</div>
  <script src="{{ route('api-tester.file', ['file' => 'api-tester.js']) }}"></script>
</body>
</html>
