<!DOCTYPE html>
<html>
<head>
    <title>Погода</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .weather-images {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .weather-images img {
            border: 2px solid #ccc;
            max-width: 700;
            height: auto;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); 
        }
    </style>
</head>
<body>
    <h1>Погода в містах України:</h1>
    <div class="weather-images">
        <img src="picture.php?gorod=4944">
        <img src="picture.php?gorod=5053">
        <img src="picture.php?gorod=5080">
        <img src="picture.php?gorod=5077">
    </div>
    <h1>Погода у світі:</h1>
    <div class="weather-images">
        <img src="picture.php?gorod=1950">
        <img src="picture.php?gorod=471">
        <img src="picture.php?gorod=744">
        <img src="picture.php?gorod=1948">
    </div>
</body>
</html>
