<?php

namespace common\components;

use http\Exception\RuntimeException;

class Config extends \yii\base\Component
{
    public const GOOGLE_SCOPES = [
        'https://www.googleapis.com/auth/userinfo.email', // доступ до адреси електронної пошти
        'https://www.googleapis.com/auth/userinfo.profile' // доступ до інформації профілю
    ];

    // Посилання на аутентифікацію
    public const GOOGLE_AUTH_URI = 'https://accounts.google.com/o/oauth2/auth';

    // Посилання на отримання токена
    public const GOOGLE_TOKEN_URI = 'https://accounts.google.com/o/oauth2/token';

    // Посилання на отримання інформації про користувача
    public const GOOGLE_USER_INFO_URI = 'https://www.googleapis.com/oauth2/v1/userinfo';

    public const GOOGLE_CLIENT_ID = '518985853604-85mlpc3ot7bs092ojsuuc4511ooofo58.apps.googleusercontent.com';
    public const GOOGLE_CLIENT_SECRET = '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';
    //public const GOOGLE_REDIRECT_URI = 'http://map.lol/auth/google';
    public const GOOGLE_REDIRECT_URI = 'https://map.transsearch.net/auth/google';

    public const BUILDING_TYPE = 1;
    public const ARCHITECT_STYLE = 2;
    public const ARCHITECT_SUB_STYLE = 3;
    public const ARCHITECT = 4;
    public const BUILDING_SUB_TYPE = 5;

    public const CATEGORY_MAP = [
        1 => 'Тип будинку',
        2 => 'Архітектурний стиль',
        3 => 'Архітектурний під стиль',
        4 => 'Архітектор',
        5 => 'Підтип будинку',
    ];

    /**
     * @return array[]
     */
    public static function getCategoriesConfig(): array
    {
        return [
            [
                'id' => 1,
                'key' => 'building_type',
                'parent_id' => null
            ],
            [
                'id' => 2,
                'key' => 'architectural_style',
                'parent_id' => null
            ],
            [
                'id' => 3,
                'key' => 'architectural_substyle',
                'parent_id' => 2
            ],
            [
                'id' => 4,
                'key' => 'architect',
                'parent_id' => null
            ],
            [
                'id' => 5,
                'key' => 'building_subtype',
                'parent_id' => 1
            ]
        ];
    }

    /**
     * @param int $categoryId
     * @return array
     */
    public static function getCategory(int $categoryId): array
    {
        $result = [];

        $categoriesConfig = self::getCategoriesConfig();

        foreach ($categoriesConfig as $config) {
            if ($config['id'] === $categoryId) {
                $result = $config;
            }
        }
        if (empty($result)) {
            throw new RuntimeException('Invalid category ID = ' . $categoryId);
        }

        return $result;
    }
}
