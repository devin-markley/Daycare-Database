<!DOCTYPE html>
<html>
<head>
    <title>My Home Page</title>
</head>
<body>
    <h1>Welcome to my home page!</h1>
    <p>Here are some providers:</p>
    <ul>
        <?php foreach ($providers as $provider): ?>
            <li><?= $provider['name'] ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

