<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1"/>
    <meta property="og:site_name" content="{{ siteName }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="{{ baseUrl }}/assets/img/logo.svg"/>
    
    <title>{@ if title } {{ title }} | {@ endif } {{ siteName }}</title> 
    
    <link rel="stylesheet" type="text/css" href="{{ baseUrl }}/assets/css/style.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    {@ block head }
    {@ endblock }
</head>
<body style="background-image: url('{{ baseUrl }}/assets/img/waves.svg'); background-size: cover;">
    
    <img class="logo" src="{{ baseUrl }}/assets/img/logo.svg" alt="{{ siteName }} Logo">
    
    <main>
        <div class="container">
            <!-- Alerts -->
            {@ set success = getSuccessFlashMessage() }
            {@ set error = getErrorFlashMessage() }

            {@ if success is not empty }
                {@ include 'utils/alert.php' with {'type': 'success', 'message': success} only }
            {@ elseif error is not empty }
                {@ include 'utils/alert.php' with {'type': 'danger', 'message': error} only }
            {@ endif }

            <!-- Content -->
            {@ block content }{@ endblock }
        </div>
    </main>

    {@ block scripts }
    {@ endblock }
</body>
</html>
