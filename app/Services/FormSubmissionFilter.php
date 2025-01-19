<?php

namespace App\Services;

use App\Models\Form;
use Illuminate\Database\Eloquent\Builder;

class FormSubmissionFilter
{
    protected $query;
    protected $form;
    protected $conditions = [];

    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->query = $form->submissions()->getQuery();
    }

    public function addCondition($field, $operator, $value, $logicalOperator = 'and')
    {
        $this->conditions[] = [
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
            'logical_operator' => $logicalOperator
        ];
        return $this;
    }

    public function apply()
    {
        foreach ($this->conditions as $index => $condition) {
            $method = $index === 0 ? 'where' : strtolower($condition['logical_operator'] === 'and' ? 'where' : 'orWhere');
            
            $this->query->$method(function ($query) use ($condition) {
                $field = $this->form->fields()->where('name', $condition['field'])->first();
                
                if (!$field) return;

                switch ($field->type) {
                    case 'date':
                        $this->applyDateFilter($query, $condition);
                        break;
                    case 'time':
                        $this->applyTimeFilter($query, $condition);
                        break;
                    case 'select':
                    case 'radio':
                    case 'checkbox':
                        $this->applyMultiValueFilter($query, $condition);
                        break;
                    default:
                        $this->applyDefaultFilter($query, $condition);
                }
            });
        }

        return $this->query;
    }

    protected function applyDateFilter($query, $condition)
    {
        $value = $condition['value'];
        if (is_array($value) && !empty($value['from']) && !empty($value['to'])) {
            $query->where(function($q) use ($condition, $value) {
                $q->where('data->' . $condition['field'], '>=', $value['from'])
                  ->where('data->' . $condition['field'], '<=', $value['to']);
            });
        }
    }

    protected function applyTimeFilter($query, $condition)
    {
        $value = $condition['value'];
        if (is_array($value) && !empty($value['from']) && !empty($value['to'])) {
            $query->where(function($q) use ($condition, $value) {
                $q->where('data->' . $condition['field'], '>=', $value['from'])
                  ->where('data->' . $condition['field'], '<=', $value['to']);
            });
        }
    }

    protected function applyMultiValueFilter($query, $condition)
    {
        $values = (array)$condition['value'];
        if (!empty($values)) {
            $query->where(function ($q) use ($condition, $values) {
                foreach ($values as $index => $value) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    
                    if ($condition['operator'] === 'contains') {
                        $q->$method('data->' . $condition['field'], 'like', '%' . $value . '%');
                    } else {
                        $q->$method(
                            'data->' . $condition['field'],
                            $condition['operator'],
                            $value
                        );
                    }
                }
            });
            
            \Log::info('Filter debug:', [
                'field' => $condition['field'],
                'values' => $values,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
        }
    }

    protected function applyDefaultFilter($query, $condition)
    {
        if ($condition['value'] !== null && $condition['value'] !== '') {
            $query->where(
                'data->' . $condition['field'],
                $condition['operator'],
                $condition['value']
            );
        }
    }
} 