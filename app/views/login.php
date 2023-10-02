{@ extends 'layouts/main.php' }

{@ block content }
    {{ openForm('/', 'post') }}
        {{ inputField(model, 'username') }}
        {{ inputField(model, 'password', 'password') }}
        {{ submitButton('Login') }}
    {{ closeForm() }}
{@ endblock }