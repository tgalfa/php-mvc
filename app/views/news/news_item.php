<div data-id="{{ item.id }}" class="news-entry">
    <!-- News details -->
    <div class="news-details">
        <h3 class="news-title">{{ item.title }}</h3>
        <p class="news-content">{{ unescape(item.content) }}</p>
    </div>

    <div class="actions">
        <!-- Edit button -->
        <button class="edit-btn">
            <img src="/assets/img/pencil.svg" alt="Edit">
        </button>

        <!-- Delete Form -->
        {{ openForm('/news/' ~  item.id ~ '/delete', 'post') }}
            <button class="delete-btn" type="submit">
                <input type="hidden" name="id" value="{{ item.id }}">
                <img src="/assets/img/close.svg" alt="Delete" />
            </button>
        {{ closeForm() }}
    </div>
</div>