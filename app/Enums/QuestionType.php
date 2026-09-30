<?php

namespace App\Enums;

enum QuestionType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Number = 'number';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case Dropdown = 'dropdown';
    case Repeater = 'repeater';
    case Email = 'email';
    case Phone = 'phone';
    case Date = 'date';
    case Rating = 'rating';
}
