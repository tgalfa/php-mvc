{@ extends 'layouts/main.php' }

{@ block head }
<link rel="stylesheet" type="text/css" href="{{ baseUrl }}/assets/css/news.css"/>
{@ endblock }

{@ block content }

<!-- News Entries -->
{@ if news is not empty }
    <h3 class="section-title">All News</h3>
{@ endif }

{@ for item in news }
    {@ include 'news/news_item.php' }
{@ endfor }

<!-- News Form -->
{@ include 'news/news_form.php' }

<!-- Logout Button -->
{@ include 'utils/logout.php' }

{@ endblock }

{@ block scripts }
<script type="text/javascript" src="{{ baseUrl }}/assets/js/news.js"></script>
{@ endblock }
