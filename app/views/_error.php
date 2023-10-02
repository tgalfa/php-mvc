{@ extends 'layouts/main.php' }

{@ block content }
    <h1>{{ code }} - {{ message }}</h1>
    <!-- Home Page Button -->
    <button class="btn btn-primary" onclick="location.href='/'">
        Home Page
    </button>
{@ endblock }