<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Fields\Field;
use FluentAdmin\Fields\TextField;
use FluentAdmin\Fields\TextareaField;
use FluentAdmin\Fields\SelectField;
use FluentAdmin\Fields\CheckboxField;
use FluentAdmin\Fields\RadioField;
use FluentAdmin\Fields\PasswordField;
use FluentAdmin\Support\Escape;

/**
 * Renders a WordPress settings-style form table.
 *
 * Output: <table class="form-table" role="presentation"><tbody>
 *           <tr><th scope="row"><label for="{id}">{label}</label></th><td>{field}{description}</td></tr>
 *         </tbody></table>
 */
class FormTable extends Component
{
    /** @var Field[] */
    protected array $fields = [];

    /**
     * Add a pre-configured Field instance directly.
     *
     * @param Field $field
     * @return static
     */
    public function field(Field $field): static
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * Add a text input field.
     *
     * @param string               $name
     * @param string               $label
     * @param array<string, mixed> $options
     * @return static
     */
    public function text(string $name, string $label, array $options = []): static
    {
        $field = TextField::make($name, $label);
        $this->applyCommonOptions($field, $options);
        if (isset($options['size'])) {
            $field->size((string) $options['size']);
        }
        return $this->field($field);
    }

    /**
     * Add a password input field.
     *
     * @param string               $name
     * @param string               $label
     * @param array<string, mixed> $options
     * @return static
     */
    public function password(string $name, string $label, array $options = []): static
    {
        $field = PasswordField::make($name, $label);
        $this->applyCommonOptions($field, $options);
        return $this->field($field);
    }

    /**
     * Add a textarea field.
     *
     * @param string               $name
     * @param string               $label
     * @param array<string, mixed> $options
     * @return static
     */
    public function textarea(string $name, string $label, array $options = []): static
    {
        $field = TextareaField::make($name, $label);
        $this->applyCommonOptions($field, $options);
        if (isset($options['rows'])) {
            $field->rows((int) $options['rows']);
        }
        return $this->field($field);
    }

    /**
     * Add a select dropdown field.
     *
     * @param string                    $name
     * @param string                    $label
     * @param array<string|int, string> $choices
     * @param array<string, mixed>      $options
     * @return static
     */
    public function select(string $name, string $label, array $choices, array $options = []): static
    {
        $field = SelectField::make($name, $label, $choices);
        $this->applyCommonOptions($field, $options);
        return $this->field($field);
    }

    /**
     * Add a checkbox field.
     *
     * @param string               $name
     * @param string               $label
     * @param array<string, mixed> $options
     * @return static
     */
    public function checkbox(string $name, string $label, array $options = []): static
    {
        $field = CheckboxField::make($name, $label);
        $this->applyCommonOptions($field, $options);
        if (!empty($options['checked'])) {
            $field->checked((bool) $options['checked']);
        }
        return $this->field($field);
    }

    /**
     * Add a radio button group field.
     *
     * @param string                    $name
     * @param string                    $label
     * @param array<string|int, string> $choices
     * @param array<string, mixed>      $options
     * @return static
     */
    public function radio(string $name, string $label, array $choices, array $options = []): static
    {
        $field = RadioField::make($name, $label, $choices);
        $this->applyCommonOptions($field, $options);
        return $this->field($field);
    }

    /**
     * Apply common options to any Field instance.
     *
     * @param Field                $field
     * @param array<string, mixed> $options
     * @return void
     */
    protected function applyCommonOptions(Field $field, array $options): void
    {
        if (isset($options['value'])) {
            $field->value($options['value']);
        }
        if (isset($options['placeholder'])) {
            $field->placeholder((string) $options['placeholder']);
        }
        if (isset($options['description'])) {
            $field->description((string) $options['description']);
        }
        if (!empty($options['required'])) {
            $field->required();
        }
        if (!empty($options['disabled'])) {
            $field->disabled();
        }
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $rows = '';
        foreach ($this->fields as $field) {
            $id = Escape::attr($field->getId());
            $label = Escape::html($field->getLabel());
            $fieldHtml = $field->render();

            $rows .= '<tr>'
                . '<th scope="row"><label for="' . $id . '">' . $label . '</label></th>'
                . '<td>' . $fieldHtml . '</td>'
                . '</tr>';
        }

        return '<table class="form-table" role="presentation"><tbody>'
            . $rows
            . '</tbody></table>';
    }
}
