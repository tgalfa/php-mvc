<!-- Set News form container -->
<div class="form-container">
    <h3 class="section-title">
        {{ model.id ? 'Update' : 'Create' }} News
    </h3>

    <!-- Set News form action to Create -->
    <div class="actions {{ model.id ? 'show' : 'hide' }}">
        <button type="button" class="btn btn-danger">
            <img src="/assets/img/close.svg" alt="Close" />
        </button>
    </div>

    {@ set url = model.id > 0 ? '/news/' ~ model.id ~ '/update' : '/news' }

    {{ openForm(url, 'post', 'news-form') }}
        {{ inputField(model, 'title') }}
        {{ textareaField(model, 'content') }}
        {{ submitButton(model.id ? 'Save' : 'Create') }}
    {{ closeForm() }}
</div>