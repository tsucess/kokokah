<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'translatable_type',
        'translatable_id',
        'language_code',
        'field_name',
        'field_value'
    ];

    // Relationships
    public function translatable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeForLanguage($query, $languageCode)
    {
        return $query->where('language_code', $languageCode);
    }

    public function scopeForType($query, $type)
    {
        return $query->where('translatable_type', $type);
    }

    public function scopeForField($query, $fieldName)
    {
        return $query->where('field_name', $fieldName);
    }

    // Methods
    public static function translate($model, $languageCode, $fieldName)
    {
        $translation = self::where('translatable_type', get_class($model))
                          ->where('translatable_id', $model->id)
                          ->where('language_code', $languageCode)
                          ->where('field_name', $fieldName)
                          ->first();

        return $translation ? $translation->field_value : null;
    }

    public static function setTranslation($model, $languageCode, $fieldName, $value)
    {
        return self::updateOrCreate(
            [
                'translatable_type' => get_class($model),
                'translatable_id' => $model->id,
                'language_code' => $languageCode,
                'field_name' => $fieldName
            ],
            ['field_value' => $value]
        );
    }

    public static function getTranslations($model, $languageCode)
    {
        $translations = self::where('translatable_type', get_class($model))
                           ->where('translatable_id', $model->id)
                           ->where('language_code', $languageCode)
                           ->get();

        $result = [];
        foreach ($translations as $translation) {
            $result[$translation->field_name] = $translation->field_value;
        }

        return $result;
    }

    public static function setTranslations($model, $languageCode, $translations)
    {
        foreach ($translations as $fieldName => $value) {
            self::setTranslation($model, $languageCode, $fieldName, $value);
        }
    }
}

