<?php

namespace App\Models;

use App\Core\DbModel;

class News extends DbModel
{
    public int $id = 0;
    public string $title = '';
    public string $content = '';

    /**
     * Get the table associated with the model.
     */
    public static function tableName(): string
    {
        return 'news';
    }

    /**
     * Attributes for mass assignment.
     */
    public function attributes(): array
    {
        return ['title', 'content'];
    }

    /**
     * Get the rules for the model.
     */
    public function rules(): array
    {
        return [
            'content' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'title' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 3], [self::RULE_MAX, 'max' => 255]],
        ];
    }

    /**
     * Get the labels for the model.
     */
    public function labels(): array
    {
        return [
            'content' => 'Description',
            'title' => 'Title',
        ];
    }
}
