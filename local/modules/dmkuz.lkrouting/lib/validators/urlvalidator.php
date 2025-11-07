<?php
namespace Dmkuz\Lkrouting\Validators;

use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Fields\Validators;

class UrlValidator extends Validators\Validator
{
    public function validate($value, $primary, array $row, Fields\Field $field)
    {
        // Проверяем, что значение не пустое
        if (empty($value)) {
            return $this->getErrorMessage($value, $field, 'URL не может быть пустым');
        }

        // Проверяем, что URL не начинается и не заканчивается слешем
        if (substr($value, 0, 1) === '/' || substr($value, -1) === '/') {
            return $this->getErrorMessage($value, $field, 'URL не должен начинаться или заканчиваться слешем (/)');
        }

        // Проверяем, что URL содержит только английские буквы, цифры и слеши
        if (!preg_match('/^[a-zA-Z0-9\/]+$/', $value)) {
            return $this->getErrorMessage($value, $field, 'URL может содержать только английские буквы, цифры и слеши (/)');
        }

        // Дополнительная проверка на множественные слеши подряд
        if (strpos($value, '//') !== false) {
            return $this->getErrorMessage($value, $field, 'URL не должен содержать множественные слеши подряд');
        }

        return true;
    }
}