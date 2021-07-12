<html>
<head>
<title>Uplaod Test</title>
</head>
<body>
    <form action="{{ url('upload') }}" method="POST" enctype="multipart/form-data">
        <input type="file" name="user_file"><br/>
        <input type="submit" value="Upload">
    </form>
</body>
</html>